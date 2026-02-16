<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassSchedule;
use App\Models\Session;
use App\Models\Student;
use App\Models\StudyClass;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $teacherId = auth()->id();
        $tenantId = session('current_tenant_id');

        $classIds = StudyClass::where('tenant_id', $tenantId)
            ->where('teacher_id', $teacherId)
            ->pluck('id');

        $studentsCount = Student::whereIn('class_id', $classIds)->count();
        $activeStudentsCount = Student::whereIn('class_id', $classIds)->where('status', 'active')->count();
        $graduatedStudentsCount = Student::whereIn('class_id', $classIds)->where('status', 'graduated')->count();
        $sessionsToday = Session::where('teacher_id', $teacherId)
            ->where('tenant_id', $tenantId)
            ->whereDate('date', now()->toDateString())
            ->count();

        // Total ayahs (all time)
        $totalNewAyahs = Session::where('teacher_id', $teacherId)
            ->where('tenant_id', $tenantId)
            ->where('session_type', 'new')
            ->where('attendance_status', 'present')
            ->sum('ayah_count');

        // Total ayahs this month
        $totalAyahsThisMonth = Session::where('teacher_id', $teacherId)
            ->where('tenant_id', $tenantId)
            ->where('session_type', 'new')
            ->where('attendance_status', 'present')
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('ayah_count');

        // Calculate attendance rate (last 30 days)
        $attendanceStats = Session::where('teacher_id', $teacherId)
            ->where('tenant_id', $tenantId)
            ->where('date', '>=', now()->subDays(30))
            ->selectRaw('
                SUM(CASE WHEN attendance_status = "present" THEN 1 ELSE 0 END) as present_count,
                COUNT(*) as total_sessions
            ')
            ->first();

        $attendanceRate = $attendanceStats->total_sessions > 0
            ? round(($attendanceStats->present_count / $attendanceStats->total_sessions) * 100, 1)
            : 0;

        $avgScore = Session::where('teacher_id', $teacherId)
            ->where('tenant_id', $tenantId)
            ->avg('score');

        // Monthly Data (Last 6 Months)
        $startMonth = now()->startOfMonth()->subMonths(5)->toDateString();

        $monthlyAyahs = Session::where('teacher_id', $teacherId)
            ->where('tenant_id', $tenantId)
            ->where('session_type', 'new')
            ->where('attendance_status', 'present')
            ->where('date', '>=', $startMonth)
            ->selectRaw("DATE_FORMAT(date, '%Y-%m-01') as month_start, SUM(ayah_count) as total")
            ->groupBy('month_start')
            ->orderBy('month_start')
            ->get();

        $monthlyReviewAyahs = Session::where('teacher_id', $teacherId)
            ->where('tenant_id', $tenantId)
            ->where('session_type', 'revision')
            ->where('attendance_status', 'present')
            ->where('date', '>=', $startMonth)
            ->selectRaw("DATE_FORMAT(date, '%Y-%m-01') as month_start, SUM(ayah_count) as total")
            ->groupBy('month_start')
            ->orderBy('month_start')
            ->get();

        $monthlyScores = Session::where('teacher_id', $teacherId)
            ->where('tenant_id', $tenantId)
            ->where('attendance_status', 'present')
            ->where('date', '>=', $startMonth)
            ->selectRaw("DATE_FORMAT(date, '%Y-%m-01') as month_start, AVG(score) as avg_score")
            ->groupBy('month_start')
            ->orderBy('month_start')
            ->get();

        $monthlyAttendance = Session::where('teacher_id', $teacherId)
            ->where('tenant_id', $tenantId)
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

        // Top 10 students (last 30 days) - teacher's students only
        $topStudents = Student::whereIn('students.class_id', $classIds)
            ->where('students.status', 'active')
            ->leftJoin('sessions', function ($join) use ($teacherId, $tenantId) {
                $join->on('students.id', '=', 'sessions.student_id')
                    ->where('sessions.teacher_id', $teacherId)
                    ->where('sessions.tenant_id', $tenantId)
                    ->where('sessions.session_type', 'new')
                    ->where('sessions.attendance_status', 'present')
                    ->where('sessions.date', '>=', now()->subDays(30));
            })
            ->select('students.*', DB::raw('COALESCE(SUM(sessions.ayah_count), 0) as total_memorized'))
            ->groupBy('students.id')
            ->orderByDesc('total_memorized')
            ->limit(10)
            ->get();

        // At-risk students (no sessions in last 14 days) - teacher's students only
        $atRiskStudents = Student::whereIn('students.class_id', $classIds)
            ->where('students.status', 'active')
            ->leftJoin('sessions', function ($join) use ($teacherId, $tenantId) {
                $join->on('students.id', '=', 'sessions.student_id')
                    ->where('sessions.teacher_id', $teacherId)
                    ->where('sessions.tenant_id', $tenantId);
            })
            ->select('students.*', DB::raw('MAX(sessions.date) as last_session_date'))
            ->groupBy('students.id')
            ->havingRaw('last_session_date IS NULL OR last_session_date < ?', [now()->subDays(14)->toDateString()])
            ->limit(10)
            ->get();

        // Class-wise performance (last 30 days) - teacher's classes only
        $classPerformance = StudyClass::whereIn('classes.id', $classIds)
            ->where('classes.is_active', true)
            ->leftJoin('students', 'classes.id', '=', 'students.class_id')
            ->leftJoin('sessions', function ($join) use ($teacherId, $tenantId) {
                $join->on('students.id', '=', 'sessions.student_id')
                    ->where('sessions.teacher_id', $teacherId)
                    ->where('sessions.tenant_id', $tenantId)
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

        return view('teacher.dashboard', compact(
            'studentsCount',
            'activeStudentsCount',
            'graduatedStudentsCount',
            'sessionsToday',
            'attendanceRate',
            'totalNewAyahs',
            'totalAyahsThisMonth',
            'avgScore',
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

    public function schedule()
    {
        $teacherId = auth()->id();
        $tenantId = session('current_tenant_id');

        $allSchedules = ClassSchedule::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->whereHas('studyClass', function ($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })
            ->with('studyClass')
            ->orderBy('start_time')
            ->get();

        $schedules = [
            'sunday' => $allSchedules->where('day_of_week', 'sunday')->values(),
            'monday' => $allSchedules->where('day_of_week', 'monday')->values(),
            'tuesday' => $allSchedules->where('day_of_week', 'tuesday')->values(),
            'wednesday' => $allSchedules->where('day_of_week', 'wednesday')->values(),
            'thursday' => $allSchedules->where('day_of_week', 'thursday')->values(),
            'friday' => $allSchedules->where('day_of_week', 'friday')->values(),
            'saturday' => $allSchedules->where('day_of_week', 'saturday')->values(),
        ];

        $dayNames = [
            'sunday' => 'الأحد',
            'monday' => 'الاثنين',
            'tuesday' => 'الثلاثاء',
            'wednesday' => 'الأربعاء',
            'thursday' => 'الخميس',
            'friday' => 'الجمعة',
            'saturday' => 'السبت',
        ];

        return view('teacher.schedule', compact('schedules', 'dayNames'));
    }
}
