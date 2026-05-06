<?php

namespace App\Http\Controllers;

use App\Models\Servis;
use App\Models\ServisLog;
use Illuminate\Http\Request;

class ServisController extends Controller
{
    /**
     * Detail servis — accessible by both pelanggan (own only) and teknisi (all)
     */
    public function show(Servis $servis)
    {
        $user = auth()->user();

        // Pelanggan can only see their own servis
        if ($user->isPelanggan() && $servis->user_id !== $user->id) {
            abort(403);
        }

        $logs = $servis->logs()->with('updatedByUser')->get();

        return view('servis.show', compact('servis', 'logs'));
    }

    /**
     * Update harga final (teknisi only)
     * Used when actual repair cost differs from initial estimate
     */
    public function updateHarga(Request $request, Servis $servis)
    {
        $request->validate([
            'estimasi_harga' => 'required|numeric|min:0',
            'catatan_harga'  => 'required|string|max:500',
        ]);

        $hargaLama = $servis->estimasi_harga;
        $hargaBaru = $request->estimasi_harga;

        $servis->update([
            'estimasi_harga' => $hargaBaru,
        ]);

        // Log the price change
        $catatan = "Harga diperbarui: Rp " . number_format($hargaLama, 0, ',', '.')
                 . " → Rp " . number_format($hargaBaru, 0, ',', '.')
                 . ". Alasan: " . $request->catatan_harga;

        ServisLog::create([
            'servis_id'  => $servis->id,
            'status'     => $servis->status,
            'catatan'    => $catatan,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('dashboard')
            ->with('pesan', 'harga_berhasil')
            ->with('tiket_highlight', $servis->nomor_tiket);
    }
}
