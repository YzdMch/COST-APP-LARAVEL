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
        $totalDibatalkan = $semuaServis->where('status', 'Dibatalkan')->count();
        $totalProses  = $totalServis - $totalSelesai - $totalDibatalkan;

        // Breakdown per status
        $statusBreakdown = [];
        foreach (['Diterima', 'Sedang dicek', 'Perbaikan', 'Testing', 'Selesai', 'Dibatalkan'] as $st) {
            $count = $semuaServis->where('status', $st)->count();
            if ($count > 0) $statusBreakdown[$st] = $count;
        }


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

        // Pembatalan baru (last 48 jam) di cabang ini
        $pembatalanBaru = Servis::where('status', 'Dibatalkan')
            ->where('cancelled_at', '>=', now()->subHours(48))
            ->when($cabangId, fn($q) => $q->where('cabang_id', $cabangId))
            ->count();

        return view('dashboard.teknisi', compact(
            'semuaServis', 'totalServis', 'totalSelesai', 'totalProses',
            'statusBreakdown', 'recentLogs', 'totalRevenue', 'cabangNama', 'pembatalanBaru'
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
