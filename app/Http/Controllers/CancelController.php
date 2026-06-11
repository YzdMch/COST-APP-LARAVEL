<?php

namespace App\Http\Controllers;

use App\Models\Servis;
use App\Models\ServisLog;
use Illuminate\Http\Request;

class CancelController extends Controller
{
    public function cancel(Request $request, Servis $servis)
    {
        $user = auth()->user();

        // Pelanggan can only cancel their own servis
        if ($user->isPelanggan() && $servis->user_id !== $user->id) {
            abort(403);
        }

        // Only admin or the owning customer can cancel
        if (!$user->isAdmin() && !$user->isPelanggan()) {
            abort(403);
        }

        // Only cancellable when status is 'Diterima'
        if (!$servis->isCancellable()) {
            return back()->withErrors([
                'cancel' => 'Pembatalan tidak bisa dilakukan. Servis sudah mulai diproses oleh teknisi.'
            ]);
        }

        $request->validate([
            'alasan_batal' => 'required|string|min:10|max:500',
        ], [
            'alasan_batal.required' => 'Alasan pembatalan wajib diisi.',
            'alasan_batal.min'      => 'Alasan minimal 10 karakter.',
        ]);

        // Update servis
        $servis->update([
            'status'       => 'Dibatalkan',
            'alasan_batal' => $request->alasan_batal,
            'cancelled_at' => now(),
            'cancelled_by' => $user->id,
        ]);

        // Create log entry
        ServisLog::create([
            'servis_id'  => $servis->id,
            'status'     => 'Dibatalkan',
            'catatan'    => 'Booking dibatalkan. Alasan: ' . $request->alasan_batal,
            'updated_by' => $user->id,
        ]);

        return redirect()->route('dashboard')
            ->with('pesan', 'batal_berhasil')
            ->with('tiket_highlight', $servis->nomor_tiket);
    }
}
