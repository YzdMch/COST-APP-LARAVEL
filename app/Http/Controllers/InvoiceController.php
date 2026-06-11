<?php

namespace App\Http\Controllers;

use App\Models\InvoiceItem;
use App\Models\Servis;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Show the invoice item editor (teknisi/admin only)
     */
    public function edit(Servis $servis)
    {
        $user = auth()->user();

        // Only teknisi or admin
        if ($user->isPelanggan()) {
            abort(403);
        }

        // Teknisi can only edit invoice for servis in their cabang
        if ($user->isTeknisi() && $servis->cabang_id !== $user->cabang_id) {
            abort(403);
        }

        $items = $servis->invoiceItems()->with('createdBy')->get();

        return view('servis.invoice-edit', compact('servis', 'items'));
    }

    /**
     * Add a new item to the invoice
     */
    public function addItem(Request $request, Servis $servis)
    {
        $user = auth()->user();
        if ($user->isPelanggan()) abort(403);

        $request->validate([
            'nama_item'    => 'required|string|max:255',
            'qty'          => 'required|integer|min:1|max:999',
            'harga_satuan' => 'required|numeric|min:0',
            'catatan'      => 'nullable|string|max:255',
        ]);

        InvoiceItem::create([
            'servis_id'    => $servis->id,
            'nama_item'    => $request->nama_item,
            'qty'          => $request->qty,
            'harga_satuan' => $request->harga_satuan,
            'subtotal'     => $request->qty * $request->harga_satuan,
            'catatan'      => $request->catatan,
            'created_by'   => $user->id,
        ]);

        // Recalculate estimasi_harga from all invoice items
        $this->recalculateHarga($servis);

        return back()->with('pesan', 'item_added');
    }

    /**
     * Delete an invoice item
     */
    public function deleteItem(InvoiceItem $item)
    {
        $user = auth()->user();
        if ($user->isPelanggan()) abort(403);

        $servis = $item->servis;
        $item->delete();

        // Recalculate estimasi_harga
        $this->recalculateHarga($servis);

        return back()->with('pesan', 'item_deleted');
    }

    /**
     * Recalculate servis.estimasi_harga from sum of invoice items
     * Only updates if there are items (preserves manual price if no items)
     */
    private function recalculateHarga(Servis $servis): void
    {
        $total = $servis->biaya_jasa + $servis->invoiceItems()->sum('subtotal');
        $servis->update(['estimasi_harga' => $total]);
    }
}
