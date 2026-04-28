<?php

namespace App\Http\Controllers;

use App\Models\Servis;
use App\Models\ServisLog;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    /**
     * Update status servis (teknisi only)
     */
    public function update(Request $request, Servis $servis)
    {
        $request->validate([
            'status'  => 'required|in:Diterima,Sedang dicek,Perbaikan,Testing,Selesai',
            'catatan' => 'nullable|string|max:1000',
            'foto'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Handle photo upload
        $foto = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto')->store('uploads', 'public');
            $foto = basename($foto);
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

        return redirect()->route('dashboard');
    }
}
