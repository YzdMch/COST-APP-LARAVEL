<?php

namespace App\Http\Controllers;

use App\Models\InvoiceItem;
use App\Models\Servis;
use App\Models\ServisLog;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    /**
     * Status order — can only move EXACTLY ONE STEP forward.
     */
    private const STATUS_ORDER = ['Diterima', 'Sedang dicek', 'Perbaikan', 'Testing', 'Selesai'];

    public function update(Request $request, Servis $servis)
    {
        // Cannot update a cancelled service
        if ($servis->status === 'Dibatalkan') {
            return back()->withErrors(['status' => 'Servis yang sudah dibatalkan tidak dapat diperbarui.']);
        }

        $currentIdx = array_search($servis->status, self::STATUS_ORDER);
        $nextStatus = self::STATUS_ORDER[$currentIdx + 1] ?? null;

        if (!$nextStatus) {
            return back()->withErrors(['status' => 'Servis sudah selesai, tidak ada status berikutnya.']);
        }

        $request->validate([
            'catatan'      => 'required|string|max:2000',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'harga_baru'   => 'nullable|numeric|min:0',
            'items'        => 'nullable|array|max:20',
            'items.*.nama_item'    => 'required_with:items|string|max:255',
            'items.*.qty'          => 'required_with:items|integer|min:1|max:999',
            'items.*.harga_satuan' => 'required_with:items|numeric|min:0',
            'items.*.catatan'      => 'nullable|string|max:255',
        ]);

        // Upload foto ke Cloudinary
        $foto = null;
        if ($request->hasFile('foto')) {
            $cloudinary = new \Cloudinary\Cloudinary(env('CLOUDINARY_URL'));
            $result = $cloudinary->uploadApi()->upload(
                $request->file('foto')->getRealPath(),
                ['folder' => 'geeko-servis']
            );
            $foto = $result['secure_url'];
        }

        // Auto-assign to the teknisi if not assigned yet
        $updateData = ['status' => $nextStatus];
        if (is_null($servis->teknisi_id) && auth()->user()->role === 'teknisi') {
            $updateData['teknisi_id'] = auth()->id();
            $updateData['assigned_at'] = now();
        }

        // Set completed_at if moving to Selesai
        if ($nextStatus === 'Selesai') {
            $updateData['completed_at'] = now();
        }

        // Update servis status
        $servis->update($updateData);

        // Save invoice items (parts/komponen yang dibeli)
        $existingItemIds = [];
        if ($request->filled('items')) {
            foreach ($request->items as $item) {
                if (empty($item['nama_item'])) continue;
                $qty = (int) $item['qty'];
                $harga = (float) $item['harga_satuan'];
                
                if (!empty($item['id'])) {
                    $invItem = InvoiceItem::find($item['id']);
                    if ($invItem && $invItem->servis_id == $servis->id) {
                        $invItem->update([
                            'nama_item'    => $item['nama_item'],
                            'qty'          => $qty,
                            'harga_satuan' => $harga,
                            'subtotal'     => $qty * $harga,
                            'catatan'      => $item['catatan'] ?? $invItem->catatan,
                        ]);
                        $existingItemIds[] = $invItem->id;
                    }
                } else {
                    $newInv = InvoiceItem::create([
                        'servis_id'    => $servis->id,
                        'nama_item'    => $item['nama_item'],
                        'qty'          => $qty,
                        'harga_satuan' => $harga,
                        'subtotal'     => $qty * $harga,
                        'catatan'      => $item['catatan'] ?? null,
                        'created_by'   => auth()->id(),
                    ]);
                    $existingItemIds[] = $newInv->id;
                }
            }
        }
        
        // Delete items that were removed from the modal
        InvoiceItem::where('servis_id', $servis->id)
            ->whereNotIn('id', $existingItemIds)
            ->delete();

        // Update biaya_jasa dan estimasi_harga
        $catatanLog = $request->catatan;
        if ($request->filled('biaya_jasa')) {
            $biayaJasaBaru = (float) $request->biaya_jasa;
            if ($servis->biaya_jasa != $biayaJasaBaru) {
                $catatanLog .= "\n\nBiaya Jasa Servis diubah menjadi: Rp " . number_format($biayaJasaBaru, 0, ',', '.');
                $servis->biaya_jasa = $biayaJasaBaru;
            }
        }

        $totalItems = InvoiceItem::where('servis_id', $servis->id)->sum('subtotal');
        $servis->estimasi_harga = $servis->biaya_jasa + $totalItems;
        $servis->save();
        if ($request->filled('items')) {
            $partsList = collect($request->items)
                ->filter(fn($i) => !empty($i['nama_item']))
                ->map(fn($i) => $i['nama_item'] . ' (x' . $i['qty'] . ')')
                ->implode(', ');
            if ($partsList) {
                $catatanLog .= "\n\nParts: " . $partsList;
            }
        }

        ServisLog::create([
            'servis_id'  => $servis->id,
            'status'     => $nextStatus,
            'catatan'    => $catatanLog,
            'foto'       => $foto,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('dashboard')
            ->with('pesan', 'status_berhasil')
            ->with('tiket_highlight', $servis->nomor_tiket);
    }
}
