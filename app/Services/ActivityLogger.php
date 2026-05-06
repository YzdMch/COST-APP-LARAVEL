<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    /**
     * Log an activity.
     */
    public static function log(
        string $action,
        string $description,
        ?string $modelType = null,
        ?int $modelId = null,
        ?array $oldValues = null,
        ?array $newValues = null,
    ): ActivityLog {
        return ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => $action,
            'model_type'  => $modelType,
            'model_id'    => $modelId,
            'description' => $description,
            'old_values'  => $oldValues,
            'new_values'  => $newValues,
            'ip_address'  => Request::ip(),
        ]);
    }

    public static function login(): void
    {
        static::log('login', Auth::user()->name . ' berhasil login');
    }

    public static function logout(): void
    {
        static::log('logout', Auth::user()->name . ' logout');
    }
}
