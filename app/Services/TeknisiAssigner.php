<?php

namespace App\Services;

use App\Models\Servis;
use App\Models\SlaConfig;
use App\Models\User;

class TeknisiAssigner
{
    /**
     * Auto-assign a servis to the teknisi with the least active workload.
     * Optionally filter by cabang_id.
     */
    public static function autoAssign(Servis $servis): ?User
    {
        $query = User::where('role', 'teknisi')
            ->where('is_active', true);

        // Filter by cabang if the servis has one
        if ($servis->cabang_id) {
            $query->where('cabang_id', $servis->cabang_id);
        }

        $teknisiList = $query->get();

        if ($teknisiList->isEmpty()) return null;

        // Find teknisi with least active (non-completed) servis
        $bestTeknisi = $teknisiList->sortBy(function ($t) {
            return $t->activeServisCount();
        })->first();

        // Assign
        $servis->update([
            'teknisi_id'  => $bestTeknisi->id,
            'assigned_at' => now(),
        ]);

        // Set SLA target
        $slaTarget = SlaConfig::findTarget(
            $servis->jenis_kerusakan,
            $servis->perangkat,
            $servis->cabang_id,
        );

        if ($slaTarget) {
            $servis->update(['sla_target_jam' => $slaTarget]);
        }

        // Log the assignment
        ActivityLogger::log(
            'assign',
            "Servis {$servis->nomor_tiket} di-assign otomatis ke teknisi {$bestTeknisi->name}",
            'Servis',
            $servis->id,
            null,
            ['teknisi_id' => $bestTeknisi->id, 'teknisi_name' => $bestTeknisi->name],
        );

        return $bestTeknisi;
    }

    /**
     * Manually assign a servis to a specific teknisi.
     */
    public static function manualAssign(Servis $servis, User $teknisi): void
    {
        $oldTeknisi = $servis->teknisi;

        $servis->update([
            'teknisi_id'  => $teknisi->id,
            'assigned_at' => now(),
        ]);

        // Set SLA target
        $slaTarget = SlaConfig::findTarget(
            $servis->jenis_kerusakan,
            $servis->perangkat,
            $servis->cabang_id,
        );

        if ($slaTarget) {
            $servis->update(['sla_target_jam' => $slaTarget]);
        }

        ActivityLogger::log(
            'assign',
            "Servis {$servis->nomor_tiket} di-assign manual ke teknisi {$teknisi->name}" .
            ($oldTeknisi ? " (sebelumnya: {$oldTeknisi->name})" : ''),
            'Servis',
            $servis->id,
            $oldTeknisi ? ['teknisi_id' => $oldTeknisi->id] : null,
            ['teknisi_id' => $teknisi->id],
        );
    }

    /**
     * Get workload summary for all active teknisi.
     * Returns collection of teknisi with their active servis counts.
     */
    public static function getWorkloadSummary(?int $cabangId = null): \Illuminate\Support\Collection
    {
        $query = User::where('role', 'teknisi')
            ->where('is_active', true)
            ->withCount(['assignedServis as active_servis_count' => function ($q) {
                $q->where('status', '!=', 'Selesai');
            }])
            ->withCount(['assignedServis as completed_servis_count' => function ($q) {
                $q->where('status', 'Selesai');
            }]);

        if ($cabangId) {
            $query->where('cabang_id', $cabangId);
        }

        return $query->orderBy('active_servis_count')->get();
    }
}
