<?php

namespace App\Services;

use App\Models\Session;
use App\Models\Student;
use App\Models\StudyClass;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Cache duration in seconds (5 minutes)
     */
    private const CACHE_TTL = 300;

    /**
     * Get dashboard statistics for a tenant (cached)
     */
    public function getTenantStatistics(int $tenantId): array
    {
        return Cache::remember("dashboard_stats_{$tenantId}", self::CACHE_TTL, function () use ($tenantId) {
            return [
                'students_count' => Student::where('tenant_id', $tenantId)->count(),
                'active_students_count' => Student::where('tenant_id', $tenantId)->where('status', 'active')->count(),
                'graduated_students_count' => Student::where('tenant_id', $tenantId)->where('status', 'graduated')->count(),
                'teachers_count' => \App\Models\TenantUser::where('tenant_id', $tenantId)->where('role', 'teacher')->count(),
                'classes_count' => StudyClass::where('tenant_id', $tenantId)->count(),
                'sessions_today' => Session::where('tenant_id', $tenantId)
                    ->whereDate('date', now()->toDateString())
                    ->count(),
            ];
        });
    }

    /**
     * Get attendance rate for a tenant (last 30 days) (cached)
     */
    public function getAttendanceRate(int $tenantId): float
    {
        return Cache::remember("attendance_rate_{$tenantId}", self::CACHE_TTL, function () use ($tenantId) {
            $stats = Session::where('tenant_id', $tenantId)
                ->where('date', '>=', now()->subDays(30))
                ->selectRaw('
                    SUM(CASE WHEN attendance_status = "present" THEN 1 ELSE 0 END) as present_count,
                    COUNT(*) as total_sessions
                ')
                ->first();

            return $stats->total_sessions > 0
                ? round(($stats->present_count / $stats->total_sessions) * 100, 1)
                : 0;
        });
    }

    /**
     * Get monthly chart data for dashboard (cached)
     */
    public function getMonthlyChartData(int $tenantId, int $months = 6): array
    {
        return Cache::remember("monthly_chart_{$tenantId}_{$months}", self::CACHE_TTL, function () use ($tenantId, $months) {
            return $this->calculateMonthlyChartData($tenantId, $months);
        });
    }

    /**
     * Calculate monthly chart data (uncached)
     */
    private function calculateMonthlyChartData(int $tenantId, int $months = 6): array
    {
        $startMonth = now()->startOfMonth()->subMonths($months - 1)->toDateString();

        $monthlyAyahs = Session::where('tenant_id', $tenantId)
            ->where('session_type', 'new')
            ->where('attendance_status', 'present')
            ->where('date', '>=', $startMonth)
            ->selectRaw("DATE_FORMAT(date, '%Y-%m-01') as month_start, SUM(ayah_count) as total")
            ->groupBy('month_start')
            ->orderBy('month_start')
            ->get();

        $monthlyScores = Session::where('tenant_id', $tenantId)
            ->where('attendance_status', 'present')
            ->where('date', '>=', $startMonth)
            ->selectRaw("DATE_FORMAT(date, '%Y-%m-01') as month_start, AVG(score) as avg_score")
            ->groupBy('month_start')
            ->orderBy('month_start')
            ->get();

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

        $labels = [];
        $ayahs = [];
        $scores = [];
        $present = [];
        $absent = [];
        $excused = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->startOfMonth()->subMonths($i);
            $monthStart = $date->format('Y-m-01');
            $labels[] = $date->format('Y-m');

            $ayahs[] = (int)($monthlyAyahs->firstWhere('month_start', $monthStart)?->total ?? 0);
            $scores[] = round((float)($monthlyScores->firstWhere('month_start', $monthStart)?->avg_score ?? 0), 2);
            $present[] = (int)($monthlyAttendance->firstWhere('month_start', $monthStart)?->present_count ?? 0);
            $absent[] = (int)($monthlyAttendance->firstWhere('month_start', $monthStart)?->absent_count ?? 0);
            $excused[] = (int)($monthlyAttendance->firstWhere('month_start', $monthStart)?->excused_count ?? 0);
        }

        return [
            'labels' => $labels,
            'ayahs' => $ayahs,
            'scores' => $scores,
            'present' => $present,
            'absent' => $absent,
            'excused' => $excused,
        ];
    }

    /**
     * Get top performing students (last 30 days) (cached)
     */
    public function getTopStudents(int $tenantId, int $limit = 10): array
    {
        return Cache::remember("top_students_{$tenantId}_{$limit}", self::CACHE_TTL, function () use ($tenantId, $limit) {
            return $this->calculateTopStudents($tenantId, $limit);
        });
    }

    /**
     * Calculate top performing students (uncached)
     */
    private function calculateTopStudents(int $tenantId, int $limit = 10): array
    {
        return Student::where('students.tenant_id', $tenantId)
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
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Get at-risk students (no sessions in last 14 days) (cached)
     */
    public function getAtRiskStudents(int $tenantId, int $limit = 10): array
    {
        return Cache::remember("at_risk_students_{$tenantId}_{$limit}", self::CACHE_TTL, function () use ($tenantId, $limit) {
            return $this->calculateAtRiskStudents($tenantId, $limit);
        });
    }

    /**
     * Calculate at-risk students (uncached)
     */
    private function calculateAtRiskStudents(int $tenantId, int $limit = 10): array
    {
        return Student::where('students.tenant_id', $tenantId)
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
            ->limit($limit)
            ->get()
            ->toArray();
    }

    /**
     * Clear all dashboard cache for a tenant
     */
    public function clearCache(int $tenantId): void
    {
        Cache::forget("dashboard_stats_{$tenantId}");
        Cache::forget("attendance_rate_{$tenantId}");
        Cache::forget("monthly_chart_{$tenantId}_6");
        Cache::forget("top_students_{$tenantId}_10");
        Cache::forget("at_risk_students_{$tenantId}_10");
    }
}
