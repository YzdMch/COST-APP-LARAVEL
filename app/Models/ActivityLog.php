<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Human-readable action labels
     */
    public static function actionLabels(): array
    {
        return [
            'login'          => 'Login',
            'logout'         => 'Logout',
            'create'         => 'Buat Data',
            'update'         => 'Edit Data',
            'delete'         => 'Hapus Data',
            'assign'         => 'Assign Teknisi',
            'status_update'  => 'Update Status',
            'price_update'   => 'Update Harga',
            'reset_password' => 'Reset Password',
            'toggle_active'  => 'Toggle Akun',
            'role_change'    => 'Ganti Role',
        ];
    }

    public static function actionColors(): array
    {
        return [
            'login'          => 'bg-blue-100 text-blue-700',
            'logout'         => 'bg-gray-100 text-gray-700',
            'create'         => 'bg-green-100 text-green-700',
            'update'         => 'bg-yellow-100 text-yellow-700',
            'delete'         => 'bg-red-100 text-red-700',
            'assign'         => 'bg-indigo-100 text-indigo-700',
            'status_update'  => 'bg-amber-100 text-amber-700',
            'price_update'   => 'bg-violet-100 text-violet-700',
            'reset_password' => 'bg-orange-100 text-orange-700',
            'toggle_active'  => 'bg-pink-100 text-pink-700',
            'role_change'    => 'bg-cyan-100 text-cyan-700',
        ];
    }
}
