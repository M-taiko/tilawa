<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::where('tenant_id', session('current_tenant_id'))
            ->with('user');

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by user
        if ($request->filled('user')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user . '%');
            });
        }

        // Filter by date
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        $logs = $query->latest()->paginate(20)->withQueryString();

        return view('admin.activity-logs.index', compact('logs'));
    }
}
