<?php

namespace App\Http\Controllers\Teacher;

use App\Exports\SessionsExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    /**
     * Export teacher's sessions to Excel
     */
    public function sessions(Request $request)
    {
        $tenantId = session('current_tenant_id');
        $teacherId = auth()->id();
        $filters = $request->only(['student_id', 'session_type', 'attendance_status', 'date_from', 'date_to']);

        $filename = 'my_sessions_' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(
            new SessionsExport($tenantId, $teacherId, $filters),
            $filename
        );
    }
}
