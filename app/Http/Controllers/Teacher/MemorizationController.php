<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentMemorizationAssignment;
use App\Models\MemorizationTest;
use App\Models\Surah;
use App\Models\Notification;
use App\Services\MemorizationTrackingService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MemorizationController extends Controller
{
    public function __construct(
        private MemorizationTrackingService $memorizationService
    ) {}

    /**
     * صفحة متابعة الحفظ للطالب
     */
    public function show(Student $student): View
    {
        $progressMap = $this->memorizationService->getDetailedProgressMap($student);
        $currentPage = $this->memorizationService->getCurrentMemorizationPage($student);

        $assignments = StudentMemorizationAssignment::where('student_id', $student->id)
            ->with('surah')
            ->latest('assigned_date')
            ->paginate(10);

        $tests = MemorizationTest::where('student_id', $student->id)
            ->with('surah')
            ->latest('test_date')
            ->take(5)
            ->get();

        $stats = $this->memorizationService->getStudentMemorizationStats($student);

        return view('teacher.memorization.show', compact(
            'student',
            'progressMap',
            'currentPage',
            'assignments',
            'tests',
            'stats'
        ));
    }

    /**
     * فتح المصحف للصفحة الحالية
     */
    public function openQuranPage(Student $student): RedirectResponse
    {
        $currentPage = $this->memorizationService->getCurrentMemorizationPage($student);

        return redirect()->route('quran.page', [
            'pageNumber' => $currentPage['page_number'],
            'student_id' => $student->id,
            'highlight_start' => $currentPage['highlight_start'],
            'highlight_end' => $currentPage['highlight_end'],
            'mode' => 'teacher',
        ]);
    }

    /**
     * إنشاء مقطع حفظ جديد
     */
    public function storeAssignment(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate([
            'surah_id' => 'required|exists:surahs,id',
            'start_ayah' => 'required|integer|min:1',
            'end_ayah' => 'required|integer|min:1',
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $this->memorizationService->assignMemorizationRange(
            student: $student,
            surahId: $validated['surah_id'],
            startAyah: $validated['start_ayah'],
            endAyah: $validated['end_ayah'],
            teacherId: auth()->id(),
            dueDate: $validated['due_date'] ?? null,
            notes: $validated['notes'] ?? null
        );

        return redirect()
            ->route('teacher.memorization.show', $student)
            ->with('success', 'تم إنشاء المقطع بنجاح');
    }

    /**
     * تسجيل اختبار جديد
     */
    public function storeTest(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate([
            'surah_id' => 'required|exists:surahs,id',
            'start_ayah' => 'required|integer|min:1',
            'end_ayah' => 'required|integer|min:1',
            'total_score' => 'required|integer|min:0|max:100',
            'memorization_accuracy' => 'nullable|integer|min:0|max:100',
            'tajweed_quality' => 'nullable|integer|min:0|max:100',
            'mistakes_count' => 'nullable|integer|min:0',
            'test_type' => 'required|in:random,sequential,full_surah',
            'test_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        MemorizationTest::create([
            'tenant_id' => $student->tenant_id,
            'student_id' => $student->id,
            'teacher_id' => auth()->id(),
            'surah_id' => $validated['surah_id'],
            'start_ayah' => $validated['start_ayah'],
            'end_ayah' => $validated['end_ayah'],
            'total_score' => $validated['total_score'],
            'memorization_accuracy' => $validated['memorization_accuracy'] ?? null,
            'tajweed_quality' => $validated['tajweed_quality'] ?? null,
            'mistakes_count' => $validated['mistakes_count'] ?? 0,
            'test_type' => $validated['test_type'],
            'test_date' => $validated['test_date'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('teacher.memorization.show', $student)
            ->with('success', 'تم تسجيل الاختبار بنجاح');
    }

    /**
     * تأكيد حفظ الطالب للآيات المحددة
     */
    public function confirmMemorization(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate([
            'surah_id' => 'required|exists:surahs,id',
            'start_ayah' => 'required|integer|min:1',
            'end_ayah' => 'required|integer|min:1',
            'page_number' => 'required|integer|min:1|max:604',
            'notes' => 'nullable|string|max:1000',
        ]);

        $surah = Surah::findOrFail($validated['surah_id']);

        // تحديث موضع الطالب الحالي
        $student->update([
            'current_surah_id' => $validated['surah_id'],
            'current_ayah' => $validated['end_ayah'],
        ]);

        // البحث عن assignment مرتبط وتحديثه
        $assignment = StudentMemorizationAssignment::where('student_id', $student->id)
            ->where('surah_id', $validated['surah_id'])
            ->where('start_ayah', '<=', $validated['end_ayah'])
            ->where('end_ayah', '>=', $validated['start_ayah'])
            ->whereIn('status', ['assigned', 'in_progress'])
            ->first();

        if ($assignment) {
            $assignment->update([
                'status' => 'completed',
                'completed_date' => now(),
                'notes' => $validated['notes'] ?? $assignment->notes,
            ]);
        }

        // إنشاء إشعار للطالب
        Notification::create([
            'tenant_id' => $student->tenant_id,
            'user_id' => $student->user_id,
            'type' => 'memorization_confirmed',
            'title' => '✅ تم تأكيد حفظك',
            'message' => sprintf(
                'أحسنت! تم تأكيد حفظك لسورة %s من الآية %d إلى الآية %d. بارك الله في حفظك!',
                $surah->name_arabic,
                $validated['start_ayah'],
                $validated['end_ayah']
            ),
            'data' => json_encode([
                'surah_id' => $validated['surah_id'],
                'surah_name' => $surah->name_arabic,
                'start_ayah' => $validated['start_ayah'],
                'end_ayah' => $validated['end_ayah'],
                'page_number' => $validated['page_number'],
                'teacher_id' => auth()->id(),
                'teacher_name' => auth()->user()->name,
            ]),
            'read_at' => null,
        ]);

        return redirect()
            ->route('teacher.memorization.show', $student)
            ->with('success', sprintf(
                'تم تأكيد حفظ الطالب %s للآيات من %d إلى %d من سورة %s',
                $student->name,
                $validated['start_ayah'],
                $validated['end_ayah'],
                $surah->name_arabic
            ));
    }
}
