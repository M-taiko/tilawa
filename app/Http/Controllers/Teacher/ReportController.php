<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(private ReportService $reportService) {}

    /**
     * Teacher's personal performance dashboard
     */
    public function index(Request $request)
    {
        $teacherId = auth()->id();
        $tenantId = session('current_tenant_id');
        $startDate = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // Get teacher's personal stats
        $stats = $this->reportService->getTeacherReport($tenantId, $teacherId, $startDate, $endDate);

        // Get teacher's top performing students
        $topStudents = $this->reportService->getTeacherTopStudents($tenantId, $teacherId, $startDate, $endDate, 10);

        // Get students needing attention (inactive or low scores)
        $inactiveStudents = $this->reportService->getTeacherInactiveStudents($tenantId, $teacherId, 14);

        // Get session trends (last 6 months)
        $sessionTrends = $this->reportService->getTeacherSessionTrends($tenantId, $teacherId, 6);

        // Get average scores by student
        $studentScores = $this->reportService->getTeacherStudentScores($tenantId, $teacherId, $startDate, $endDate);

        // Get attendance breakdown
        $attendanceBreakdown = $this->reportService->getTeacherAttendanceBreakdown($tenantId, $teacherId, $startDate, $endDate);

        return view('teacher.reports.index', compact(
            'stats',
            'topStudents',
            'inactiveStudents',
            'sessionTrends',
            'studentScores',
            'attendanceBreakdown',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Individual student progress report (for teacher's students only)
     */
    public function student(Request $request, int $studentId)
    {
        $teacherId = auth()->id();
        $tenantId = session('current_tenant_id');
        $startDate = $request->input('start_date', now()->subMonths(3)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // Verify teacher has access to this student (via assigned class)
        $student = \App\Models\Student::where('tenant_id', $tenantId)
            ->whereHas('class', function ($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })
            ->with(['class', 'sessions' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate])
                    ->orderBy('date', 'desc');
            }])
            ->findOrFail($studentId);

        // Get student progress metrics
        $progressMetrics = $this->reportService->getStudentProgressMetrics($tenantId, $studentId, $startDate, $endDate);

        return view('teacher.reports.student', compact(
            'student',
            'progressMetrics',
            'startDate',
            'endDate'
        ));
    }
}
