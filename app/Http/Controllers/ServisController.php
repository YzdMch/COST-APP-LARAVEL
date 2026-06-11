<?php

namespace App\Http\Controllers;

use App\Models\Servis;
use App\Models\ServisLog;
use Illuminate\Http\Request;

class ServisController extends Controller
{
    /**
     * Detail servis — accessible by both pelanggan (own only) and teknisi/admin (all)
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
     * Invoice servis — only accessible if status is Selesai
     */
    public function invoice(Servis $servis)
    {
        $user = auth()->user();

        // Pelanggan can only see their own invoice
        if ($user->isPelanggan() && $servis->user_id !== $user->id) {
            abort(403);
        }

        // Invoice only available for completed services
        if ($servis->status !== 'Selesai') {
            return redirect()->route('servis.show', $servis)
                ->with('error', 'Invoice hanya tersedia untuk servis yang sudah selesai.');
        }

        $logs  = $servis->logs()->with('updatedByUser')->get();
        $items = $servis->invoiceItems()->with('createdBy')->get();

        return view('servis.invoice', compact('servis', 'logs', 'items'));
    }
}
