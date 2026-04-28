<?php

namespace App\Http\Controllers;

use App\Models\Servis;
use App\Models\ServisLog;
use Illuminate\Http\Request;

class ServisController extends Controller
{
    /**
     * Detail servis (pelanggan)
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
     * Edit form (teknisi only)
     */
    public function edit(Servis $servis)
    {
        return view('servis.edit', compact('servis'));
    }

    /**
     * Update servis data (teknisi only)
     */
    public function update(Request $request, Servis $servis)
    {
        $request->validate([
            'nama_pelanggan'  => 'required|string|min:3|max:100',
            'email'           => 'required|email|max:150',
            'no_telepon'      => 'required|string|min:9|max:20',
            'perangkat'       => 'required|in:macbook,windows,pc,imac,other',
            'jenis_kerusakan' => 'required|in:lcd,battery,ssd,thermal,other',
            'status'          => 'required|in:Diterima,Sedang dicek,Perbaikan,Testing,Selesai',
            'estimasi_harga'  => 'nullable|numeric|min:0',
            'deskripsi'       => 'required|string|min:5',
        ]);

        $servis->update($request->only([
            'nama_pelanggan', 'email', 'no_telepon', 'perangkat',
            'jenis_kerusakan', 'status', 'estimasi_harga', 'deskripsi',
        ]));

        // Log the edit
        ServisLog::create([
            'servis_id'  => $servis->id,
            'status'     => $request->status,
            'catatan'    => 'Data servis diperbarui oleh teknisi.',
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('dashboard')
            ->with('pesan', 'edit_berhasil')
            ->with('tiket_highlight', $servis->nomor_tiket);
    }

    /**
     * Delete servis (teknisi only)
     */
    public function destroy(Servis $servis)
    {
        $servis->delete();

        return redirect()->route('dashboard')
            ->with('pesan', 'hapus_berhasil');
    }
}
