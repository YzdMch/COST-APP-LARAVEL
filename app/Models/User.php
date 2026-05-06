<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'no_telepon',
        'role',
        'cabang_id',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function servis(): HasMany
    {
        return $this->hasMany(Servis::class);
    }

    public function cabang(): BelongsTo
    {
        return $this->belongsTo(Cabang::class);
    }

    /**
     * Servis yang ditugaskan ke teknisi ini
     */
    public function assignedServis(): HasMany
    {
        return $this->hasMany(Servis::class, 'teknisi_id');
    }

    /**
     * Servis aktif (belum selesai) yang ditugaskan ke teknisi ini
     */
    public function activeServisCount(): int
    {
        return $this->assignedServis()->where('status', '!=', 'Selesai')->count();
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function isPelanggan(): bool
    {
        return $this->role === 'pelanggan';
    }

    public function isTeknisi(): bool
    {
        return $this->role === 'teknisi';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
