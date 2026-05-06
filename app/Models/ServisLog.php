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

    /**
     * Get the full URL for the photo.
     * Supports:
     *  - Cloudinary URL (https://res.cloudinary.com/...)
     *  - Relative path (uploads/xxx.jpg) — legacy local storage
     *  - Basename only (xxx.jpg) — oldest legacy data
     */
    public function getFotoUrlAttribute(): ?string
    {
        if (!$this->foto) return null;

        // Cloudinary URL — already a full URL, return as-is
        if (str_starts_with($this->foto, 'http')) {
            return $this->foto;
        }

        // Local storage fallback (legacy data)
        $path = str_contains($this->foto, '/') ? $this->foto : 'uploads/' . $this->foto;
        return asset('storage/' . $path);
    }
}
