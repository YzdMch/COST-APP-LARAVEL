<?php

namespace App\Http\Controllers;

use App\Models\Servis;
use App\Models\ServisLog;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    /**
     * Status order — can only move forward, never backward.
     */
    private const STATUS_ORDER = ['Diterima', 'Sedang dicek', 'Perbaikan', 'Testing', 'Selesai'];

    public function update(Request $request, Servis $servis)
    {
        $request->validate([
            'status'  => 'required|in:Diterima,Sedang dicek,Perbaikan,Testing,Selesai',
            'catatan' => 'required|string|max:1000',
            'foto'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Enforce forward-only progression
        $currentIdx = array_search($servis->status, self::STATUS_ORDER);
        $newIdx     = array_search($request->status, self::STATUS_ORDER);

        if ($newIdx === false || $newIdx <= $currentIdx) {
            return back()->withErrors(['status' => 'Status hanya bisa dimajukan ke tahap berikutnya.']);
        }

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

        // Update servis status
        $servis->update(['status' => $request->status]);

        // Create log entry
        ServisLog::create([
            'servis_id'  => $servis->id,
            'status'     => $request->status,
            'catatan'    => $request->catatan,
            'foto'       => $foto,
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('dashboard')
            ->with('pesan', 'status_berhasil')
            ->with('tiket_highlight', $servis->nomor_tiket);
    }
}
