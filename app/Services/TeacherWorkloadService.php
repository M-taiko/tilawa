<?php

namespace App\Services;

use App\Models\Session;
use App\Models\Student;
use App\Models\StudyClass;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TeacherWorkloadService
{
    /**
     * Get workload statistics for a teacher
     */
    public function getTeacherWorkload(int $teacherId, int $tenantId): array
    {
        // Active students count
        $activeStudents = Student::whereHas('class', function ($query) use ($teacherId) {
            $query->where('teacher_id', $teacherId);
        })
        ->where('tenant_id', $tenantId)
        ->where('status', 'active')
        ->count();

        // Active classes count
        $activeClasses = StudyClass::where('teacher_id', $teacherId)
            ->where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->count();

        // Sessions this week
        $sessionsThisWeek = Session::where('teacher_id', $teacherId)
            ->where('tenant_id', $tenantId)
            ->whereBetween('date', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])
            ->count();

        // Average sessions per day (last 30 days)
        $avgSessionsPerDay = Session::where('teacher_id', $teacherId)
            ->where('tenant_id', $tenantId)
            ->where('date', '>=', now()->subDays(30))
            ->count() / 30;

        // Attendance rate (last 30 days)
        $attendanceStats = Session::where('teacher_id', $teacherId)
            ->where('tenant_id', $tenantId)
            ->where('date', '>=', now()->subDays(30))
            ->selectRaw('
                SUM(CASE WHEN attendance_status = "present" THEN 1 ELSE 0 END) as present,
                COUNT(*) as total
            ')
            ->first();

        $attendanceRate = $attendanceStats->total > 0
            ? round(($attendanceStats->present / $attendanceStats->total) * 100, 1)
            : 0;

        // Average score (last 30 days)
        $avgScore = Session::where('teacher_id', $teacherId)
            ->where('tenant_id', $tenantId)
            ->where('date', '>=', now()->subDays(30))
            ->where('attendance_status', 'present')
            ->avg('score');

        return [
            'active_students' => $activeStudents,
            'active_classes' => $activeClasses,
            'sessions_this_week' => $sessionsThisWeek,
            'avg_sessions_per_day' => round($avgSessionsPerDay, 1),
            'attendance_rate' => $attendanceRate,
            'avg_score' => round($avgScore ?? 0, 2),
            'workload_level' => $this->calculateWorkloadLevel($activeStudents, $sessionsThisWeek),
        ];
    }

    /**
     * Get all teachers' workload for a tenant
     */
    public function getAllTeachersWorkload(int $tenantId): array
    {
        $teachers = User::whereHas('tenants', function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId)->where('role', 'teacher');
        })
        ->where('is_active', true)
        ->get();

        $workloads = [];
        foreach ($teachers as $teacher) {
            $workloads[] = [
                'teacher_id' => $teacher->id,
                'teacher_name' => $teacher->name,
                'workload' => $this->getTeacherWorkload($teacher->id, $tenantId),
            ];
        }

        // Sort by workload level
        usort($workloads, function ($a, $b) {
            $levels = ['low' => 1, 'normal' => 2, 'high' => 3, 'overloaded' => 4];
            return ($levels[$b['workload']['workload_level']] ?? 0) <=> ($levels[$a['workload']['workload_level']] ?? 0);
        });

        return $workloads;
    }

    /**
     * Calculate workload level
     */
    private function calculateWorkloadLevel(int $students, int $sessionsPerWeek): string
    {
        if ($students > 30 || $sessionsPerWeek > 25) {
            return 'overloaded';
        } elseif ($students > 20 || $sessionsPerWeek > 15) {
            return 'high';
        } elseif ($students > 10 || $sessionsPerWeek > 8) {
            return 'normal';
        }

        return 'low';
    }
}
