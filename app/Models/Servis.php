<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Servis extends Model
{
    protected $table = 'servis';
    public $timestamps = false;

    const UPDATED_AT = null;
    const CREATED_AT = 'created_at';

    protected $fillable = [
        'nomor_tiket',
        'user_id',
        'nama_pelanggan',
        'email',
        'no_telepon',
        'perangkat',
        'jenis_kerusakan',
        'cabang',
        'cabang_id',
        'deskripsi',
        'estimasi_harga',
        'foto',
        'status',
        'teknisi_id',
        'assigned_at',
        'sla_target_jam',
        'completed_at',
    ];

    protected $casts = [
        'estimasi_harga' => 'decimal:2',
        'created_at' => 'datetime',
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function teknisi(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teknisi_id');
    }

    public function cabangRelasi(): BelongsTo
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(ServisLog::class)->orderBy('updated_at');
    }

    /**
     * Generate unique ticket number: GK-YYYYMMDD-XXXX
     */
    public static function generateNomorTiket(): string
    {
        $tanggal = date('Ymd');
        $randomSuffix = strtoupper(substr(uniqid(), -4));
        return 'GK-' . $tanggal . '-' . $randomSuffix;
    }

    /**
     * Check if service has exceeded SLA target
     */
    public function isOverSla(): bool
    {
        if (!$this->sla_target_jam) return false;
        if ($this->status === 'Selesai') return false;

        $startTime = $this->assigned_at ?? $this->created_at;
        $deadline = $startTime->copy()->addHours($this->sla_target_jam);

        return Carbon::now()->greaterThan($deadline);
    }

    /**
     * Get SLA remaining hours (negative = overdue)
     */
    public function slaRemainingHours(): ?float
    {
        if (!$this->sla_target_jam) return null;

        $startTime = $this->assigned_at ?? $this->created_at;
        $deadline = $startTime->copy()->addHours($this->sla_target_jam);

        if ($this->status === 'Selesai' && $this->completed_at) {
            return $deadline->diffInHours($this->completed_at, false);
        }

        return $deadline->diffInHours(Carbon::now(), false);
    }

    /**
     * Get completion time in hours (from created_at to completed_at)
     */
    public function completionTimeHours(): ?float
    {
        if (!$this->completed_at) return null;
        return $this->created_at->diffInHours($this->completed_at);
    }

    /**
     * Label maps for display
     */
    public static function labelPerangkat(): array
    {
        return [
            'macbook' => 'MacBook Pro / Air',
            'windows' => 'Windows Laptop',
            'pc'      => 'Desktop PC',
            'imac'    => 'iMac / Mac Desktop',
            'other'   => 'Lainnya',
        ];
    }

    public static function labelKerusakan(): array
    {
        return [
            'lcd'     => 'Layar Pecah / LCD Rusak',
            'battery' => 'Baterai Kembang / Drop',
            'ssd'     => 'Upgrade SSD',
            'thermal' => 'Thermal Paste / Cleaning',
            'other'   => 'Lainnya',
        ];
    }

    public static function statusClasses(): array
    {
        return [
            'Diterima'     => 'bg-blue-100 text-blue-700',
            'Sedang dicek' => 'bg-yellow-100 text-yellow-800',
            'Perbaikan'    => 'bg-orange-100 text-orange-800',
            'Testing'      => 'bg-lime-100 text-lime-700',
            'Selesai'      => 'bg-green-100 text-green-800',
        ];
    }

    public static function statusIcons(): array
    {
        return [
            'Diterima'     => 'fa-inbox',
            'Sedang dicek' => 'fa-search',
            'Perbaikan'    => 'fa-wrench',
            'Testing'      => 'fa-vial',
            'Selesai'      => 'fa-check-circle',
        ];
    }
}
