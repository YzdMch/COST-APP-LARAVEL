<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use App\Models\SlaConfig;
use App\Models\Servis;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class SlaController extends Controller
{
    public function index()
    {
        $slaConfigs = SlaConfig::with('cabang')
            ->orderBy('jenis_kerusakan')
            ->orderBy('perangkat')
            ->get();

        // Overdue services
        $overdueServis = Servis::where('status', '!=', 'Selesai')
            ->whereNotNull('sla_target_jam')
            ->with(['teknisi', 'cabangRelasi'])
            ->get()
            ->filter(fn ($s) => $s->isOverSla())
            ->values();

        // SLA compliance per month
        $cabangList = Cabang::where('is_active', true)->get();

        return view('admin.sla.index', compact('slaConfigs', 'overdueServis', 'cabangList'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jenis_kerusakan' => 'required|in:lcd,battery,ssd,thermal,other',
            'perangkat'       => 'nullable|in:macbook,windows,pc,imac,other',
            'target_jam'      => 'required|integer|min:1',
            'cabang_id'       => 'nullable|exists:cabang,id',
        ]);

        // Set null for empty strings
        if (empty($data['perangkat'])) $data['perangkat'] = null;
        if (empty($data['cabang_id'])) $data['cabang_id'] = null;

        $sla = SlaConfig::create($data);

        ActivityLogger::log(
            'create', "SLA config baru: {$data['jenis_kerusakan']} - {$data['target_jam']} jam",
            'SlaConfig', $sla->id, null, $data
        );

        return redirect()->route('admin.sla.index')
            ->with('pesan', 'Konfigurasi SLA berhasil ditambahkan.');
    }

    public function update(Request $request, SlaConfig $sla)
    {
        $data = $request->validate([
            'jenis_kerusakan' => 'required|in:lcd,battery,ssd,thermal,other',
            'perangkat'       => 'nullable|in:macbook,windows,pc,imac,other',
            'target_jam'      => 'required|integer|min:1',
            'cabang_id'       => 'nullable|exists:cabang,id',
        ]);

        if (empty($data['perangkat'])) $data['perangkat'] = null;
        if (empty($data['cabang_id'])) $data['cabang_id'] = null;

        $oldData = $sla->only(['jenis_kerusakan', 'perangkat', 'target_jam', 'cabang_id']);
        $sla->update($data);

        ActivityLogger::log(
            'update', "SLA config diupdate: {$data['jenis_kerusakan']} - {$data['target_jam']} jam",
            'SlaConfig', $sla->id, $oldData, $data
        );

        return redirect()->route('admin.sla.index')
            ->with('pesan', 'Konfigurasi SLA berhasil diperbarui.');
    }

    public function destroy(SlaConfig $sla)
    {
        ActivityLogger::log(
            'delete', "SLA config dihapus: {$sla->jenis_kerusakan}",
            'SlaConfig', $sla->id, $sla->toArray(), null
        );

        $sla->delete();

        return redirect()->route('admin.sla.index')
            ->with('pesan', 'Konfigurasi SLA berhasil dihapus.');
    }
}
