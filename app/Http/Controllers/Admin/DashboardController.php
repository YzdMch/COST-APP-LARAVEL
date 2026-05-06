<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\Servis;
use App\Models\User;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Key stats
        $totalServis = Servis::count();
        $totalSelesai = Servis::where('status', 'Selesai')->count();
        $totalProses = $totalServis - $totalSelesai;
        $totalRevenue = Servis::where('status', 'Selesai')->sum('estimasi_harga');
        $totalUsers = User::where('role', 'pelanggan')->count();
        $totalTeknisi = User::where('role', 'teknisi')->where('is_active', true)->count();
        $totalCabang = Cabang::where('is_active', true)->count();

        // SLA compliance
        $completedWithSla = Servis::where('status', 'Selesai')
            ->whereNotNull('sla_target_jam')
            ->whereNotNull('completed_at')
            ->get();
        $slaOnTime = $completedWithSla->filter(fn ($s) => !$s->isOverSla())->count();
        $slaCompliance = $completedWithSla->count() > 0
            ? round(($slaOnTime / $completedWithSla->count()) * 100, 1)
            : 100;

        // Overdue services
        $overdueCount = Servis::where('status', '!=', 'Selesai')
            ->whereNotNull('sla_target_jam')
            ->get()
            ->filter(fn ($s) => $s->isOverSla())
            ->count();

        // Monthly trend (last 6 months)
        $monthlyTrend = Servis::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as bulan"),
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN status = 'Selesai' THEN 1 ELSE 0 END) as selesai"),
                DB::raw("SUM(CASE WHEN status = 'Selesai' THEN estimasi_harga ELSE 0 END) as revenue")
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(6)->startOfMonth())
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Top kerusakan
        $topKerusakan = Servis::select('jenis_kerusakan', DB::raw('COUNT(*) as total'))
            ->groupBy('jenis_kerusakan')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Top perangkat
        $topPerangkat = Servis::select('perangkat', DB::raw('COUNT(*) as total'))
            ->groupBy('perangkat')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Status breakdown
        $statusBreakdown = Servis::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status');

        // Teknisi performance
        $teknisiPerformance = User::where('role', 'teknisi')
            ->where('is_active', true)
            ->withCount(['assignedServis as total_servis'])
            ->withCount(['assignedServis as servis_selesai' => fn ($q) => $q->where('status', 'Selesai')])
            ->withCount(['assignedServis as servis_aktif' => fn ($q) => $q->where('status', '!=', 'Selesai')])
            ->get()
            ->map(function ($t) {
                // Average completion time
                $avgTime = Servis::where('teknisi_id', $t->id)
                    ->where('status', 'Selesai')
                    ->whereNotNull('completed_at')
                    ->get()
                    ->avg(fn ($s) => $s->completionTimeHours());
                $t->avg_completion_hours = $avgTime ? round($avgTime, 1) : null;

                // Revenue generated
                $t->revenue = Servis::where('teknisi_id', $t->id)
                    ->where('status', 'Selesai')
                    ->sum('estimasi_harga');

                return $t;
            });

        // Per-cabang stats
        $cabangStats = Cabang::where('is_active', true)
            ->withCount('servis')
            ->withCount(['servis as servis_selesai' => fn ($q) => $q->where('status', 'Selesai')])
            ->withCount(['servis as servis_aktif' => fn ($q) => $q->where('status', '!=', 'Selesai')])
            ->withCount('teknisi')
            ->get();

        // Recent activity logs
        $recentLogs = ActivityLog::with('user')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalServis', 'totalSelesai', 'totalProses', 'totalRevenue',
            'totalUsers', 'totalTeknisi', 'totalCabang',
            'slaCompliance', 'overdueCount',
            'monthlyTrend', 'topKerusakan', 'topPerangkat', 'statusBreakdown',
            'teknisiPerformance', 'cabangStats', 'recentLogs'
        ));
    }
}
