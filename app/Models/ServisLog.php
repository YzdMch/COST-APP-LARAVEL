<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServisLog extends Model
{
    protected $table = 'servis_log';
    public $timestamps = false;

    protected $fillable = [
        'servis_id',
        'status',
        'catatan',
        'foto',
        'updated_by',
    ];

    protected $casts = [
        'updated_at' => 'datetime',
    ];

    public function servis(): BelongsTo
    {
        return $this->belongsTo(Servis::class);
    }

    public function updatedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
