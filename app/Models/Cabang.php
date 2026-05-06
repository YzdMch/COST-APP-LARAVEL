<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cabang extends Model
{
    protected $table = 'cabang';

    protected $fillable = [
        'nama',
        'alamat',
        'telepon',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function teknisi(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'teknisi');
    }

    public function servis(): HasMany
    {
        return $this->hasMany(Servis::class);
    }

    public function slaConfigs(): HasMany
    {
        return $this->hasMany(SlaConfig::class);
    }
}
