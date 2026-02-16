<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\Student;
use App\Models\StudyClass;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $tenantId = session('current_tenant_id');

        // Basic counts
        $studentsCount = Student::where('tenant_id', $tenantId)->count();
        $activeStudentsCount = Student::where('tenant_id', $tenantId)->where('status', 'active')->count();
        $graduatedStudentsCount = Student::where('tenant_id', $tenantId)->where('status', 'graduated')->count();
        $teachersCount = \App\Models\TenantUser::where('tenant_id', $tenantId)->where('role', 'teacher')->count();
        $classesCount = StudyClass::where('tenant_id', $tenantId)->count();
        $sessionsToday = Session::where('tenant_id', $tenantId)
            ->whereDate('date', now()->toDateString())
            ->count();

        // Calculate attendance rate (last 30 days)
        $attendanceStats = Session::where('tenant_id', $tenantId)
            ->where('date', '>=', now()->subDays(30))
            ->selectRaw('
                SUM(CASE WHEN attendance_status = "present" THEN 1 ELSE 0 END) as present_count,
                COUNT(*) as total_sessions
            ')
            ->first();

        $attendanceRate = $attendanceStats->total_sessions > 0
            ? round(($attendanceStats->present_count / $attendanceStats->total_sessions) * 100, 1)
            : 0;

        // Calculate graduation rate
        $graduationRate = $studentsCount > 0
            ? round(($graduatedStudentsCount / $studentsCount) * 100, 1)
            : 0;

        // Total ayahs this month
        $totalAyahsThisMonth = Session::where('tenant_id', $tenantId)
            ->where('session_type', 'new')
            ->where('attendance_status', 'present')
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('ayah_count');

        // Monthly Data for the last 6 months
        $startMonth = now()->startOfMonth()->subMonths(5)->toDateString();

        // 1. New Ayahs (Sum)
        $monthlyAyahs = Session::where('tenant_id', $tenantId)
            ->where('session_type', 'new')
            ->where('attendance_status', 'present')
            ->where('date', '>=', $startMonth)
            ->selectRaw("DATE_FORMAT(date, '%Y-%m-01') as month_start, SUM(ayah_count) as total")
            ->groupBy('month_start')
            ->orderBy('month_start')
            ->get();

        // 2. Revision Ayahs (Sum)
        $monthlyReviewAyahs = Session::where('tenant_id', $tenantId)
            ->where('session_type', 'revision')
            ->where('attendance_status', 'present')
            ->where('date', '>=', $startMonth)
            ->selectRaw("DATE_FORMAT(date, '%Y-%m-01') as month_start, SUM(ayah_count) as total")
            ->groupBy('month_start')
            ->orderBy('month_start')
            ->get();
            
        // 3. Average Score
        $monthlyScores = Session::where('tenant_id', $tenantId)
            ->where('attendance_status', 'present')
            ->where('date', '>=', $startMonth)
            ->selectRaw("DATE_FORMAT(date, '%Y-%m-01') as month_start, AVG(score) as avg_score")
            ->groupBy('month_start')
            ->orderBy('month_start')
            ->get();

        // 4. Attendance
        $monthlyAttendance = Session::where('tenant_id', $tenantId)
            ->where('date', '>=', $startMonth)
            ->selectRaw("
                DATE_FORMAT(date, '%Y-%m-01') as month_start,
                SUM(CASE WHEN attendance_status = 'present' THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN attendance_status = 'absent' THEN 1 ELSE 0 END) as absent_count,
                SUM(CASE WHEN attendance_status = 'excused' THEN 1 ELSE 0 END) as excused_count
            ")
            ->groupBy('month_start')
            ->orderBy('month_start')
            ->get();

        $monthLabels = [];
        $ayahsData = [];
        $reviewData = [];
        $avgScoreData = [];
        $presentData = [];
        $absentData = [];
        $excusedData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->startOfMonth()->subMonths($i);
            $monthStart = $date->format('Y-m-01');
            $monthLabels[] = $date->format('Y-m');

            $ayahsData[] = (int)($monthlyAyahs->firstWhere('month_start', $monthStart)?->total ?? 0);
            $reviewData[] = (int)($monthlyReviewAyahs->firstWhere('month_start', $monthStart)?->total ?? 0);
            $avgScoreData[] = round((float)($monthlyScores->firstWhere('month_start', $monthStart)?->avg_score ?? 0), 2);
            $presentData[] = (int)($monthlyAttendance->firstWhere('month_start', $monthStart)?->present_count ?? 0);
            $absentData[] = (int)($monthlyAttendance->firstWhere('month_start', $monthStart)?->absent_count ?? 0);
            $excusedData[] = (int)($monthlyAttendance->firstWhere('month_start', $monthStart)?->excused_count ?? 0);
        }

        // Top 10 students (last 30 days)
        $topStudents = Student::where('students.tenant_id', $tenantId)
            ->where('students.status', 'active')
            ->leftJoin('sessions', function ($join) {
                $join->on('students.id', '=', 'sessions.student_id')
                    ->where('sessions.session_type', 'new')
                    ->where('sessions.attendance_status', 'present')
                    ->where('sessions.date', '>=', now()->subDays(30));
            })
            ->select(
                'students.id',
                'students.name',
                'students.class_id',
                DB::raw('COALESCE(SUM(sessions.ayah_count), 0) as total_memorized')
            )
            ->groupBy('students.id', 'students.name', 'students.class_id')
            ->orderByDesc('total_memorized')
            ->limit(10)
            ->get();

        // At-risk students (no sessions in last 14 days)
        $atRiskStudents = Student::where('students.tenant_id', $tenantId)
            ->where('students.status', 'active')
            ->leftJoin('sessions', function ($join) use ($tenantId) {
                $join->on('students.id', '=', 'sessions.student_id')
                    ->where('sessions.tenant_id', '=', $tenantId);
            })
            ->select(
                'students.id',
                'students.name',
                'students.class_id',
                DB::raw('MAX(sessions.date) as last_session_date')
            )
            ->groupBy('students.id', 'students.name', 'students.class_id')
            ->havingRaw('last_session_date IS NULL OR last_session_date < ?', [now()->subDays(14)->toDateString()])
            ->limit(10)
            ->get();

        // Class-wise performance (last 30 days)
        $classPerformance = StudyClass::where('classes.tenant_id', $tenantId)
            ->where('classes.is_active', true)
            ->leftJoin('students', 'classes.id', '=', 'students.class_id')
            ->leftJoin('sessions', function ($join) {
                $join->on('students.id', '=', 'sessions.student_id')
                    ->where('sessions.attendance_status', 'present')
                    ->where('sessions.date', '>=', now()->subDays(30));
            })
            ->select(
                'classes.name',
                DB::raw('COUNT(DISTINCT students.id) as students_count'),
                DB::raw('COUNT(sessions.id) as sessions_count'),
                DB::raw('COALESCE(AVG(sessions.score), 0) as avg_score'),
                DB::raw('COALESCE(SUM(CASE WHEN sessions.session_type = "new" THEN sessions.ayah_count ELSE 0 END), 0) as total_ayahs')
            )
            ->groupBy('classes.id', 'classes.name')
            ->orderByDesc('total_ayahs')
            ->get();

        return view('admin.dashboard', compact(
            'studentsCount',
            'activeStudentsCount',
            'graduatedStudentsCount',
            'teachersCount',
            'classesCount',
            'sessionsToday',
            'attendanceRate',
            'graduationRate',
            'totalAyahsThisMonth',
            'monthLabels',
            'ayahsData',
            'reviewData',
            'avgScoreData',
            'presentData',
            'absentData',
            'excusedData',
            'topStudents',
            'atRiskStudents',
            'classPerformance'
        ));
    }
}
