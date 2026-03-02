<?php

namespace App\Services;

use App\Models\Student;
use App\Models\StudentMemorizationAssignment;
use App\Models\MemorizationTest;
use App\Models\Surah;
use App\Models\Verse;
use App\Models\Session;
use Illuminate\Support\Facades\DB;

class MemorizationTrackingService
{
    /**
     * الحصول على خريطة تقدم مفصلة لكل سورة
     */
    public function getDetailedProgressMap(Student $student): array
    {
        $surahs = Surah::all();
        $progressMap = [];

        foreach ($surahs as $surah) {
            // حساب التقدم بناءً على current_ayah إذا كانت السورة الحالية
            $totalMemorized = 0;
            if ($student->current_surah_id == $surah->id && $student->current_ayah) {
                $totalMemorized = $student->current_ayah;
            } elseif ($student->current_surah_id > $surah->id) {
                // إذا الطالب تجاوز السورة، تعتبر مكتملة
                $totalMemorized = $surah->ayah_count;
            }

            // حساب النسبة المئوية (نتأكد أنها لا تزيد عن 100%)
            $progressPercent = $surah->ayah_count > 0
                ? min(round(($totalMemorized / $surah->ayah_count) * 100, 1), 100)
                : 0;

            // تحديد الحالة
            $status = 'pending';
            if ($totalMemorized > 0 && $totalMemorized < $surah->ayah_count) {
                $status = 'in_progress';
            } elseif ($totalMemorized >= $surah->ayah_count) {
                $status = 'completed';
            }

            // الآية الحالية
            $currentAyah = $student->current_surah_id == $surah->id
                ? $student->current_ayah
                : null;

            // متوسط الدرجات
            $avgScore = Session::where('student_id', $student->id)
                ->where('surah_id', $surah->id)
                ->whereNotNull('memorization_score')
                ->avg('memorization_score');

            $progressMap[] = [
                'surah_id' => $surah->id,
                'surah_name' => $surah->name_arabic,
                'surah_name_english' => $surah->name_english,
                'total_ayahs' => $surah->ayah_count,
                'memorized_ayahs' => $totalMemorized,
                'progress_percent' => $progressPercent,
                'status' => $status,
                'current_ayah' => $currentAyah,
                'avg_score' => $avgScore ? round($avgScore, 1) : null,
            ];
        }

        return $progressMap;
    }

    /**
     * تحديد الصفحة التي عليها دور الحفظ
     */
    public function getCurrentMemorizationPage(Student $student): ?array
    {
        // أولاً: البحث عن assignment قيد الحفظ
        $currentAssignment = $student->currentMemorization;

        if ($currentAssignment) {
            return [
                'page_number' => $currentAssignment->page_number,
                'surah_id' => $currentAssignment->surah_id,
                'surah_name' => $currentAssignment->surah->name_arabic,
                'highlight_start' => $currentAssignment->start_ayah,
                'highlight_end' => $currentAssignment->end_ayah,
                'assignment' => $currentAssignment,
            ];
        }

        // ثانياً: استخدام current_ayah من الطالب
        if ($student->current_surah_id && $student->current_ayah) {
            $verse = Verse::where('surah_id', $student->current_surah_id)
                ->where('verse_number', $student->current_ayah)
                ->first();

            if ($verse) {
                return [
                    'page_number' => $verse->page_number,
                    'surah_id' => $student->current_surah_id,
                    'surah_name' => $student->currentSurah->name_arabic,
                    'highlight_start' => $student->current_ayah,
                    'highlight_end' => $student->current_ayah,
                    'assignment' => null,
                ];
            }
        }

        // افتراضي: الصفحة الأولى
        return [
            'page_number' => 1,
            'surah_id' => 1,
            'surah_name' => 'الفاتحة',
            'highlight_start' => 1,
            'highlight_end' => 1,
            'assignment' => null,
        ];
    }

    /**
     * إنشاء مقطع حفظ جديد
     */
    public function assignMemorizationRange(
        Student $student,
        int $surahId,
        int $startAyah,
        int $endAyah,
        int $teacherId,
        ?string $dueDate = null,
        ?string $notes = null
    ): StudentMemorizationAssignment {
        // حساب page_number تلقائياً
        $verse = Verse::where('surah_id', $surahId)
            ->where('verse_number', $startAyah)
            ->first(['page_number']);

        return StudentMemorizationAssignment::create([
            'tenant_id' => $student->tenant_id,
            'student_id' => $student->id,
            'teacher_id' => $teacherId,
            'surah_id' => $surahId,
            'start_ayah' => $startAyah,
            'end_ayah' => $endAyah,
            'page_number' => $verse?->page_number,
            'status' => 'assigned',
            'assigned_date' => now(),
            'due_date' => $dueDate,
            'notes' => $notes,
        ]);
    }

    /**
     * تحديث حالة المقطع عند الإكمال
     */
    public function completeAssignment(StudentMemorizationAssignment $assignment): void
    {
        $assignment->update([
            'status' => 'completed',
            'completed_date' => now(),
        ]);
    }

    /**
     * إحصائيات الطالب الشاملة
     */
    public function getStudentMemorizationStats(Student $student): array
    {
        $assignments = $student->memorizations;

        $totalAssignments = $assignments->count();
        $completedAssignments = $assignments->where('status', 'completed')->count();
        $inProgressAssignments = $assignments->where('status', 'in_progress')->count();

        $overdueAssignments = $assignments->filter(function ($assignment) {
            return $assignment->isOverdue();
        })->count();

        $completionRate = $totalAssignments > 0
            ? round(($completedAssignments / $totalAssignments) * 100, 1)
            : 0;

        $avgTestScore = $student->tests()
            ->avg('total_score');

        return [
            'total_assignments' => $totalAssignments,
            'completed_assignments' => $completedAssignments,
            'in_progress_assignments' => $inProgressAssignments,
            'overdue_assignments' => $overdueAssignments,
            'completion_rate' => $completionRate,
            'avg_test_score' => $avgTestScore ? round($avgTestScore, 1) : null,
            'total_tests' => $student->tests()->count(),
        ];
    }
}
