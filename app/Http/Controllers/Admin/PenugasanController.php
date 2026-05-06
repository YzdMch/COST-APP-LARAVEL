<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Servis;
use App\Models\User;
use App\Models\Cabang;
use App\Services\TeknisiAssigner;
use Illuminate\Http\Request;

class PenugasanController extends Controller
{
    public function index(Request $request)
    {
        $cabangId = $request->input('cabang_id');
        $statusFilter = $request->input('status');

        // All servis with relations
        $query = Servis::with(['teknisi', 'cabangRelasi', 'user'])
            ->when($cabangId, fn ($q) => $q->where('cabang_id', $cabangId))
            ->when($statusFilter, fn ($q) => $q->where('status', $statusFilter));

        $servisList = $query->orderByDesc('created_at')->paginate(20);

        // Quick stats
        $statsQuery = Servis::query()->when($cabangId, fn ($q) => $q->where('cabang_id', $cabangId));
        $stats = [
            'total'         => (clone $statsQuery)->count(),
            'diterima'      => (clone $statsQuery)->where('status', 'Diterima')->count(),
            'sedang_dicek'  => (clone $statsQuery)->where('status', 'Sedang dicek')->count(),
            'perbaikan'     => (clone $statsQuery)->where('status', 'Perbaikan')->count(),
            'testing'       => (clone $statsQuery)->where('status', 'Testing')->count(),
            'selesai'       => (clone $statsQuery)->where('status', 'Selesai')->count(),
            'unassigned'    => (clone $statsQuery)->whereNull('teknisi_id')->where('status', '!=', 'Selesai')->count(),
        ];

        // Overdue count
        $overdueServis = Servis::where('status', '!=', 'Selesai')
            ->whereNotNull('sla_target_jam')
            ->when($cabangId, fn ($q) => $q->where('cabang_id', $cabangId))
            ->get()
            ->filter(fn ($s) => $s->isOverSla());
        $stats['overdue'] = $overdueServis->count();

        // Workload summary
        $workload = TeknisiAssigner::getWorkloadSummary($cabangId);

        // All teknisi for assign — grouped by cabang for JS filtering
        $allTeknisi = User::where('role', 'teknisi')
            ->where('is_active', true)
            ->with('cabang')
            ->get()
            ->map(fn ($t) => [
                'id' => $t->id,
                'name' => $t->name,
                'cabang_id' => $t->cabang_id,
                'cabang_nama' => $t->cabang?->nama ?? '-',
            ]);

        $cabangList = Cabang::where('is_active', true)->get();

        return view('admin.penugasan.index', compact(
            'servisList', 'stats', 'workload', 'allTeknisi', 'cabangList', 'cabangId', 'statusFilter', 'overdueServis'
        ));
    }

    public function assign(Request $request, Servis $servis)
    {
        $request->validate([
            'teknisi_id' => 'required|exists:users,id',
        ]);

        $teknisi = User::findOrFail($request->teknisi_id);

        // Validate: teknisi must be from the same cabang as the servis
        if ($servis->cabang_id && $teknisi->cabang_id !== $servis->cabang_id) {
            return back()->withErrors([
                'error' => "Teknisi {$teknisi->name} bukan dari cabang servis ini.",
            ]);
        }

        TeknisiAssigner::manualAssign($servis, $teknisi);

        return redirect()->route('admin.penugasan.index')
            ->with('pesan', "Servis {$servis->nomor_tiket} di-assign ke {$teknisi->name}.");
    }

    public function autoAssign(Servis $servis)
    {
        $teknisi = TeknisiAssigner::autoAssign($servis);

        if (!$teknisi) {
            return back()->withErrors(['error' => 'Tidak ada teknisi yang tersedia untuk cabang ini.']);
        }

        return redirect()->route('admin.penugasan.index')
            ->with('pesan', "Servis {$servis->nomor_tiket} otomatis di-assign ke {$teknisi->name}.");
    }

    public function autoAssignAll(Request $request)
    {
        $cabangId = $request->input('cabang_id');

        $unassigned = Servis::whereNull('teknisi_id')
            ->where('status', '!=', 'Selesai')
            ->when($cabangId, fn ($q) => $q->where('cabang_id', $cabangId))
            ->get();

        $assigned = 0;
        foreach ($unassigned as $servis) {
            $teknisi = TeknisiAssigner::autoAssign($servis);
            if ($teknisi) $assigned++;
        }

        return redirect()->route('admin.penugasan.index')
            ->with('pesan', "{$assigned} servis berhasil di-assign otomatis.");
    }
}
