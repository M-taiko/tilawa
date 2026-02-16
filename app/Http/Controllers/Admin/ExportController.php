<?php

namespace App\Http\Controllers\Admin;

use App\Exports\SessionsExport;
use App\Exports\StudentsExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    /**
     * Export students to Excel
     */
    public function students(Request $request)
    {
        $tenantId = session('current_tenant_id');
        $filters = $request->only(['status', 'group', 'track', 'search']);

        $filename = 'students_' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(
            new StudentsExport($tenantId, $filters),
            $filename
        );
    }

    /**
     * Export sessions to Excel
     */
    public function sessions(Request $request)
    {
        $tenantId = session('current_tenant_id');
        $filters = $request->only(['student_id', 'session_type', 'attendance_status', 'date_from', 'date_to']);

        $filename = 'sessions_' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(
            new SessionsExport($tenantId, null, $filters),
            $filename
        );
    }
}
