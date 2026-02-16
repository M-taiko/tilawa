<?php

namespace App\Services;

use App\Models\Session;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ReportService
{
    private const CACHE_TTL = 600; // 10 minutes
    public function getTopStudents(int $tenantId, string $startDate, string $endDate, int $limit = 10)
    {
        return Cache::remember("report_top_students_{$tenantId}_{$startDate}_{$endDate}_{$limit}", self::CACHE_TTL, function () use ($tenantId, $startDate, $endDate, $limit) {
            return Student::where('students.tenant_id', $tenantId)
            ->leftJoin('sessions', function ($join) use ($startDate, $endDate) {
                $join->on('students.id', '=', 'sessions.student_id')
                    ->where('sessions.session_type', 'new')
                    ->where('sessions.attendance_status', 'present')
                    ->whereBetween('sessions.date', [$startDate, $endDate]);
            })
            ->select(
                'students.id',
                'students.name',
                'students.class_id',
                DB::raw('COALESCE(SUM(sessions.ayah_count), 0) as total_memorized')
            )
            ->groupBy('students.id', 'students.name', 'students.class_id')
            ->orderByDesc('total_memorized')
            ->limit($limit)
            ->get();
        });
    }

    public function getInactiveStudents(int $tenantId, int $days, bool $paginate = false, int $perPage = 20)
    {
        $cutoff = now()->subDays($days)->toDateString();

        $query = Student::where('students.tenant_id', $tenantId)
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
            ->havingRaw('last_session_date IS NULL OR last_session_date < ?', [$cutoff])
            ->orderBy('last_session_date');

        return $paginate ? $query->paginate($perPage) : $query->get();
    }

    public function getTeacherReport(int $tenantId, int $teacherId, string $startDate, string $endDate)
    {
        $teacher = User::whereHas('tenants', function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId)->where('role', 'teacher');
        })->findOrFail($teacherId);

        return Session::where('tenant_id', $tenantId)
            ->where('teacher_id', $teacher->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw('COUNT(*) as sessions_count, AVG(score) as avg_score, SUM(CASE WHEN session_type = "new" AND attendance_status = "present" THEN ayah_count ELSE 0 END) as total_new_ayahs, COUNT(DISTINCT student_id) as active_students')
            ->first();
    }

    /**
     * Get top performing students for a specific teacher
     */
    public function getTeacherTopStudents(int $tenantId, int $teacherId, string $startDate, string $endDate, int $limit = 10)
    {
        return Student::where('students.tenant_id', $tenantId)
            ->whereHas('class', function ($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })
            ->leftJoin('sessions', function ($join) use ($startDate, $endDate, $teacherId) {
                $join->on('students.id', '=', 'sessions.student_id')
                    ->where('sessions.teacher_id', $teacherId)
                    ->where('sessions.session_type', 'new')
                    ->where('sessions.attendance_status', 'present')
                    ->whereBetween('sessions.date', [$startDate, $endDate]);
            })
            ->select(
                'students.id',
                'students.name',
                'students.class_id',
                DB::raw('COALESCE(SUM(sessions.ayah_count), 0) as total_memorized')
            )
            ->groupBy('students.id', 'students.name', 'students.class_id')
            ->orderByDesc('total_memorized')
            ->limit($limit)
            ->get();
    }

    /**
     * Get inactive students for a specific teacher
     */
    public function getTeacherInactiveStudents(int $tenantId, int $teacherId, int $days, bool $paginate = false, int $perPage = 20)
    {
        $cutoff = now()->subDays($days)->toDateString();

        $query = Student::where('students.tenant_id', $tenantId)
            ->where('students.status', 'active')
            ->whereHas('class', function ($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })
            ->leftJoin('sessions', function ($join) use ($tenantId, $teacherId) {
                $join->on('students.id', '=', 'sessions.student_id')
                    ->where('sessions.tenant_id', '=', $tenantId)
                    ->where('sessions.teacher_id', '=', $teacherId);
            })
            ->select(
                'students.id',
                'students.name',
                'students.class_id',
                DB::raw('MAX(sessions.date) as last_session_date')
            )
            ->groupBy('students.id', 'students.name', 'students.class_id')
            ->havingRaw('last_session_date IS NULL OR last_session_date < ?', [$cutoff])
            ->orderBy('last_session_date');

        return $paginate ? $query->paginate($perPage) : $query->get();
    }

    /**
     * Get session count trends for a teacher over the last N months
     */
    public function getTeacherSessionTrends(int $tenantId, int $teacherId, int $months = 6)
    {
        $startDate = now()->subMonths($months)->startOfMonth();

        return Session::where('tenant_id', $tenantId)
            ->where('teacher_id', $teacherId)
            ->where('date', '>=', $startDate)
            ->selectRaw('DATE_FORMAT(date, "%Y-%m-01") as month, COUNT(*) as sessions_count, AVG(score) as avg_score')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    /**
     * Get average scores by student for a specific teacher
     */
    public function getTeacherStudentScores(int $tenantId, int $teacherId, string $startDate, string $endDate)
    {
        return Student::where('students.tenant_id', $tenantId)
            ->whereHas('class', function ($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })
            ->leftJoin('sessions', function ($join) use ($startDate, $endDate, $teacherId) {
                $join->on('students.id', '=', 'sessions.student_id')
                    ->where('sessions.teacher_id', $teacherId)
                    ->where('sessions.attendance_status', 'present')
                    ->whereBetween('sessions.date', [$startDate, $endDate]);
            })
            ->select('students.name', DB::raw('COALESCE(AVG(sessions.score), 0) as avg_score'))
            ->groupBy('students.id', 'students.name')
            ->orderByDesc('avg_score')
            ->limit(15)
            ->get();
    }

    /**
     * Get attendance pattern breakdown for a specific teacher
     */
    public function getTeacherAttendanceBreakdown(int $tenantId, int $teacherId, string $startDate, string $endDate)
    {
        return Session::where('tenant_id', $tenantId)
            ->where('teacher_id', $teacherId)
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw('
                SUM(CASE WHEN attendance_status = "present" THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN attendance_status = "absent" THEN 1 ELSE 0 END) as absent_count,
                SUM(CASE WHEN attendance_status = "excused" THEN 1 ELSE 0 END) as excused_count,
                COUNT(*) as total_sessions
            ')
            ->first();
    }

    /**
     * Get detailed progress metrics for a specific student
     */
    public function getStudentProgressMetrics(int $tenantId, int $studentId, string $startDate, string $endDate)
    {
        $student = Student::where('tenant_id', $tenantId)->findOrFail($studentId);

        // Get session stats
        $sessionStats = Session::where('tenant_id', $tenantId)
            ->where('student_id', $studentId)
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw('
                COUNT(*) as total_sessions,
                SUM(CASE WHEN attendance_status = "present" THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN attendance_status = "absent" THEN 1 ELSE 0 END) as absent_count,
                SUM(CASE WHEN session_type = "new" AND attendance_status = "present" THEN ayah_count ELSE 0 END) as total_new_ayahs,
                SUM(CASE WHEN session_type = "revision" AND attendance_status = "present" THEN ayah_count ELSE 0 END) as total_revision_ayahs,
                AVG(CASE WHEN attendance_status = "present" THEN score ELSE NULL END) as avg_score
            ')
            ->first();

        // Get attendance rate
        $attendanceRate = $sessionStats->total_sessions > 0
            ? round(($sessionStats->present_count / $sessionStats->total_sessions) * 100, 1)
            : 0;

        // Get monthly progress
        $monthlyProgress = Session::where('tenant_id', $tenantId)
            ->where('student_id', $studentId)
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw('DATE_FORMAT(date, "%Y-%m-01") as month, SUM(CASE WHEN session_type = "new" THEN ayah_count ELSE 0 END) as ayahs')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return [
            'session_stats' => $sessionStats,
            'attendance_rate' => $attendanceRate,
            'monthly_progress' => $monthlyProgress,
            'student' => $student,
        ];
    }

    /**
     * Clear all cached reports for a tenant
     */
    public function clearCache(int $tenantId): void
    {
        // Clear patterns by using Cache::flush() or selectively forget keys
        // For now, we'll rely on TTL expiration
        // In production, consider using cache tags with Redis
    }
}
