<?php

namespace App\Http\Controllers;

use App\Models\Servis;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isTeknisi()) {
            $semuaServis = Servis::orderBy('created_at', 'desc')->get();
            $totalServis  = $semuaServis->count();
            $totalSelesai = $semuaServis->where('status', 'Selesai')->count();
            $totalProses  = $totalServis - $totalSelesai;

            return view('dashboard.teknisi', compact('semuaServis', 'totalServis', 'totalSelesai', 'totalProses'));
        }

        // Pelanggan dashboard
        $semuaServis = Servis::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $total   = $semuaServis->count();
        $selesai = $semuaServis->where('status', 'Selesai')->count();
        $proses  = $total - $selesai;

        $tiketBaru = session('tiket_baru');

        return view('dashboard.pelanggan', compact('semuaServis', 'total', 'selesai', 'proses', 'tiketBaru'));
    }
}
