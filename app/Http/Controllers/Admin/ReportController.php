<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(private ReportService $reportService) {}

    public function index(Request $request)
    {
        $tenantId = session('current_tenant_id');
        $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $inactiveDays = (int)$request->input('inactive_days', 14);

        $topStudents = $this->reportService->getTopStudents($tenantId, $startDate, $endDate);
        $inactiveStudents = $this->reportService->getInactiveStudents($tenantId, $inactiveDays);
        $teachers = User::whereHas('tenants', function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId)->where('role', 'teacher');
        })->orderBy('name')->get();

        return view('admin.reports.index', compact('topStudents', 'inactiveStudents', 'startDate', 'endDate', 'inactiveDays', 'teachers'));
    }

    public function teacher(Request $request, int $teacherId)
    {
        $tenantId = session('current_tenant_id');
        $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $report = $this->reportService->getTeacherReport($tenantId, $teacherId, $startDate, $endDate);
        $teacher = User::whereHas('tenants', function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId)->where('role', 'teacher');
        })->findOrFail($teacherId);

        // Additional data for enhanced report
        $topStudents = $this->reportService->getTeacherTopStudents($tenantId, $teacherId, $startDate, $endDate, 5);
        $inactiveStudents = $this->reportService->getTeacherInactiveStudents($tenantId, $teacherId, 14);
        $sessionTrends = $this->reportService->getTeacherSessionTrends($tenantId, $teacherId, 6);
        $attendanceBreakdown = $this->reportService->getTeacherAttendanceBreakdown($tenantId, $teacherId, $startDate, $endDate);
        $studentScores = $this->reportService->getTeacherStudentScores($tenantId, $teacherId, $startDate, $endDate);

        return view('admin.reports.teacher', compact(
            'report',
            'teacher',
            'startDate',
            'endDate',
            'topStudents',
            'inactiveStudents',
            'sessionTrends',
            'attendanceBreakdown',
            'studentScores'
        ));
    }

    /**
     * Detailed inactive students report with pagination and sorting
     */
    public function inactiveStudents(Request $request)
    {
        $tenantId = session('current_tenant_id');
        $inactiveDays = (int)$request->input('inactive_days', 14);
        $sortBy = $request->input('sort_by', 'last_session');
        $perPage = (int)$request->input('per_page', 20);

        $inactiveStudents = $this->reportService->getInactiveStudents($tenantId, $inactiveDays, true, $perPage);

        return view('admin.reports.inactive_students', compact('inactiveStudents', 'inactiveDays', 'sortBy', 'perPage'));
    }
}
