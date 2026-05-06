<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SlaConfig extends Model
{
    protected $table = 'sla_configs';

    protected $fillable = [
        'jenis_kerusakan',
        'perangkat',
        'target_jam',
        'cabang_id',
    ];

    public function cabang(): BelongsTo
    {
        return $this->belongsTo(Cabang::class);
    }

    /**
     * Find matching SLA target for a servis record.
     * Priority: specific (kerusakan + perangkat + cabang) → generic
     */
    public static function findTarget(string $kerusakan, string $perangkat, ?int $cabangId = null): ?int
    {
        // 1. Most specific: kerusakan + perangkat + cabang
        $sla = static::where('jenis_kerusakan', $kerusakan)
            ->where('perangkat', $perangkat)
            ->where('cabang_id', $cabangId)
            ->first();
        if ($sla) return $sla->target_jam;

        // 2. Kerusakan + perangkat (any cabang)
        $sla = static::where('jenis_kerusakan', $kerusakan)
            ->where('perangkat', $perangkat)
            ->whereNull('cabang_id')
            ->first();
        if ($sla) return $sla->target_jam;

        // 3. Kerusakan only + cabang
        $sla = static::where('jenis_kerusakan', $kerusakan)
            ->whereNull('perangkat')
            ->where('cabang_id', $cabangId)
            ->first();
        if ($sla) return $sla->target_jam;

        // 4. Most generic: kerusakan only, global
        $sla = static::where('jenis_kerusakan', $kerusakan)
            ->whereNull('perangkat')
            ->whereNull('cabang_id')
            ->first();

        return $sla?->target_jam;
    }
}
