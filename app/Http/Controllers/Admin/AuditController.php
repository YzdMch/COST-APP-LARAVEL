<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user');

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('dari')) {
            $query->whereDate('created_at', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }
        if ($request->filled('search')) {
            $query->where('description', 'like', "%{$request->search}%");
        }

        $logs = $query->orderByDesc('created_at')->paginate(30);
        $actionLabels = ActivityLog::actionLabels();
        $actionColors = ActivityLog::actionColors();

        return view('admin.audit.index', compact('logs', 'actionLabels', 'actionColors'));
    }
}
