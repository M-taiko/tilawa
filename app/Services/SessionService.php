<?php

namespace App\Services;

use App\Models\Session;
use App\Models\Student;
use App\Models\StudentFoundationSkillMastery;

class SessionService
{
    /**
     * Create a new session and update student progress
     */
    public function createSession(array $data, int $tenantId, int $teacherId): Session
    {
        $payload = $this->prepareSessionPayload($data, $tenantId, $teacherId);

        $session = Session::create($payload);

        // Update student progress if applicable
        $this->syncFoundationSkills($session, $data);
        $this->updateStudentProgress($session, $data);

        return $session;
    }

    /**
     * Update an existing session and student progress
     */
    public function updateSession(Session $session, array $data): Session
    {
        $payload = $this->prepareSessionPayload($data, $session->tenant_id, $session->teacher_id);

        $session->update($payload);

        // Update student progress if applicable
        $this->syncFoundationSkills($session, $data);
        $this->updateStudentProgress($session, $data);

        return $session;
    }

    /**
     * Prepare session payload from request data
     */
    private function prepareSessionPayload(array $data, int $tenantId, int $teacherId): array
    {
        $payload = [
            'tenant_id' => $tenantId,
            'teacher_id' => $teacherId,
            'student_id' => $data['student_id'],
            'session_type' => $data['session_type'],
            'attendance_status' => $data['attendance_status'],
            'date' => $data['date'],
            'notes' => $data['notes'] ?? null,
        ];

        if ($data['session_type'] === 'foundation') {
            $payload['foundation_skill_id'] = null;
            $payload['mastery_progress'] = null;
        } else {
            $payload['surah_id'] = $data['surah_id'] ?? null;
            $payload['ayah_from'] = $data['ayah_from'] ?? null;
            $payload['ayah_to'] = $data['ayah_to'] ?? null;
            $payload['score'] = $data['score'] ?? null;

            // Calculate ayah count
            if (!empty($data['ayah_from']) && !empty($data['ayah_to'])) {
                $payload['ayah_count'] = ($data['ayah_to'] - $data['ayah_from']) + 1;
            }
        }

        return $payload;
    }

    /**
     * Update student progress based on session data
     */
    private function updateStudentProgress(Session $session): void
    {
        if ($session->attendance_status !== 'present') {
            return;
        }

        $student = $session->student;

        if ($session->session_type === 'new' && $session->surah_id && $session->ayah_to) {
            // Update student's current position for memorization track
            $student->update([
                'current_surah_id' => $session->surah_id,
                'current_ayah' => $session->ayah_to,
            ]);
        } elseif ($session->session_type === 'foundation') {
            // Foundation mastery is handled in syncFoundationSkills
            return;
        }
    }

    private function syncFoundationSkills(Session $session, array $data): void
    {
        if ($session->session_type !== 'foundation') {
            $session->foundationSkills()->sync([]);
            return;
        }

        $skillIds = $data['foundation_skill_ids'] ?? [];
        $masteryMap = $data['foundation_mastery'] ?? [];
        $pivotData = [];

        foreach ($skillIds as $skillId) {
            $mastery = isset($masteryMap[$skillId]) ? (int)$masteryMap[$skillId] : 0;
            $pivotData[$skillId] = ['mastery_percent' => $mastery];

            if ($session->attendance_status === 'present') {
                StudentFoundationSkillMastery::updateOrCreate(
                    [
                        'tenant_id' => $session->tenant_id,
                        'student_id' => $session->student_id,
                        'foundation_skill_id' => $skillId,
                    ],
                    [
                        'mastery_percent' => $mastery,
                    ]
                );
            }
        }

        $session->foundationSkills()->sync($pivotData);
    }

    /**
     * Calculate student statistics
     */
    public function getStudentStatistics(int $studentId): array
    {
        $stats = Session::where('student_id', $studentId)
            ->selectRaw("
                SUM(CASE WHEN session_type = 'new' AND attendance_status = 'present' THEN ayah_count ELSE 0 END) as total_ayahs,
                AVG(score) as avg_score,
                COUNT(*) as total_sessions,
                SUM(CASE WHEN attendance_status = 'present' THEN 1 ELSE 0 END) as present_sessions,
                SUM(CASE WHEN attendance_status = 'absent' THEN 1 ELSE 0 END) as absent_sessions,
                SUM(CASE WHEN attendance_status = 'excused' THEN 1 ELSE 0 END) as excused_sessions
            ")
            ->first();

        return [
            'total_ayahs' => (int)($stats->total_ayahs ?? 0),
            'avg_score' => round((float)($stats->avg_score ?? 0), 2),
            'total_sessions' => (int)($stats->total_sessions ?? 0),
            'present_sessions' => (int)($stats->present_sessions ?? 0),
            'absent_sessions' => (int)($stats->absent_sessions ?? 0),
            'excused_sessions' => (int)($stats->excused_sessions ?? 0),
            'memorized_percent' => round((((int)($stats->total_ayahs ?? 0)) / 6236) * 100, 2),
        ];
    }
}
