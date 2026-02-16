<?php

namespace App\Services;

use App\Models\Session;
use App\Models\Student;
use App\Models\Surah;

class QuranProgressService
{
    /**
     * Get student's Quran progress map
     */
    public function getProgressMap(Student $student): array
    {
        // Get all surahs
        $surahs = Surah::orderBy('id')->get();

        // Get student's completed sessions
        $completedSessions = Session::where('student_id', $student->id)
            ->where('session_type', 'new')
            ->where('attendance_status', 'present')
            ->select('surah_id', 'ayah_from', 'ayah_to')
            ->get()
            ->groupBy('surah_id');

        $progressMap = [];

        foreach ($surahs as $surah) {
            $surahId = $surah->id;
            $totalAyahs = $surah->ayah_count;
            $memorizedAyahs = [];

            if (isset($completedSessions[$surahId])) {
                foreach ($completedSessions[$surahId] as $session) {
                    for ($i = $session->ayah_from; $i <= $session->ayah_to; $i++) {
                        $memorizedAyahs[$i] = true;
                    }
                }
            }

            $memorizedCount = count($memorizedAyahs);
            $progress = $totalAyahs > 0 ? round(($memorizedCount / $totalAyahs) * 100, 1) : 0;

            $status = 'pending';
            if ($progress >= 100) {
                $status = 'completed';
            } elseif ($progress > 0) {
                $status = 'in_progress';
            }

            $progressMap[] = [
                'surah_id' => $surahId,
                'surah_name' => $surah->name_ar,
                'total_ayahs' => $totalAyahs,
                'memorized_ayahs' => $memorizedCount,
                'progress_percent' => $progress,
                'status' => $status,
            ];
        }

        return $progressMap;
    }

    /**
     * Get progress by Juz (30 parts of Quran)
     */
    public function getProgressByJuz(Student $student): array
    {
        $progressMap = $this->getProgressMap($student);

        // Define Juz boundaries (simplified - surah ranges)
        $juzDefinition = [
            1 => [1, 2],      // Al-Fatiha to part of Al-Baqarah
            2 => [2],
            3 => [2, 3],      // Part of Al-Baqarah to part of Al-Imran
            // ... (simplified for example)
        ];

        $juzProgress = [];
        for ($i = 1; $i <= 30; $i++) {
            $juzProgress[$i] = [
                'juz' => $i,
                'progress_percent' => 0,
                'status' => 'pending',
            ];
        }

        return $juzProgress;
    }

    /**
     * Get overall statistics
     */
    public function getOverallStatistics(Student $student): array
    {
        $totalAyahs = 6236; // Total ayahs in Quran

        $memorizedAyahs = Session::where('student_id', $student->id)
            ->where('session_type', 'new')
            ->where('attendance_status', 'present')
            ->sum('ayah_count');

        $completedSurahs = Session::where('student_id', $student->id)
            ->where('session_type', 'new')
            ->where('attendance_status', 'present')
            ->distinct('surah_id')
            ->count('surah_id');

        return [
            'total_ayahs' => $totalAyahs,
            'memorized_ayahs' => $memorizedAyahs,
            'remaining_ayahs' => $totalAyahs - $memorizedAyahs,
            'progress_percent' => round(($memorizedAyahs / $totalAyahs) * 100, 2),
            'completed_surahs' => $completedSurahs,
            'total_surahs' => 114,
        ];
    }
}
