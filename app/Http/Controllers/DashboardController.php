<?php

namespace App\Http\Controllers;

use App\Models\Servis;
use App\Models\ServisLog;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Admin redirect to admin panel
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isTeknisi()) {
            return $this->teknisiDashboard();
        }

        return $this->pelangganDashboard($user);
    }

    private function teknisiDashboard()
    {
        $user = auth()->user();
        $cabangId = $user->cabang_id;

        // Only show servis from the teknisi's cabang
        $query = Servis::orderBy('created_at', 'desc');
        if ($cabangId) {
            $query->where('cabang_id', $cabangId);
        }
        $semuaServis = $query->get();

        $totalServis  = $semuaServis->count();
        $totalSelesai = $semuaServis->where('status', 'Selesai')->count();
        $totalProses  = $totalServis - $totalSelesai;

        // Breakdown per status
        $statusBreakdown = [
            'Diterima'     => $semuaServis->where('status', 'Diterima')->count(),
            'Sedang dicek' => $semuaServis->where('status', 'Sedang dicek')->count(),
            'Perbaikan'    => $semuaServis->where('status', 'Perbaikan')->count(),
            'Testing'      => $semuaServis->where('status', 'Testing')->count(),
            'Selesai'      => $semuaServis->where('status', 'Selesai')->count(),
        ];

        // Recent logs — only from servis in this cabang
        $servisIds = $semuaServis->pluck('id');
        $recentLogs = ServisLog::with(['servis', 'updatedByUser'])
            ->whereIn('servis_id', $servisIds)
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        // Revenue estimate
        $totalRevenue = $semuaServis->where('status', 'Selesai')->sum('estimasi_harga');

        // Cabang name for display
        $cabangNama = $user->cabang?->nama ?? 'Semua Cabang';

        return view('dashboard.teknisi', compact(
            'semuaServis', 'totalServis', 'totalSelesai', 'totalProses',
            'statusBreakdown', 'recentLogs', 'totalRevenue', 'cabangNama'
        ));
    }

    private function pelangganDashboard($user)
    {
        $semuaServis = Servis::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $total   = $semuaServis->count();
        $selesai = $semuaServis->where('status', 'Selesai')->count();
        $proses  = $total - $selesai;

        // Active service (latest non-completed)
        $activeServis = $semuaServis->whereNotIn('status', ['Selesai'])->first();

        $tiketBaru = session('tiket_baru');

        return view('dashboard.pelanggan', compact(
            'semuaServis', 'total', 'selesai', 'proses', 'tiketBaru', 'activeServis'
        ));
    }
}
