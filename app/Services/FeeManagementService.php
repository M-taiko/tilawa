<?php

namespace App\Services;

use App\Models\Student;
use App\Models\StudentFee;
use App\Models\StudyClass;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FeeManagementService
{
    /**
     * تحديد رسوم لطالب
     */
    public function setStudentFee(Student $student, array $feeData): StudentFee
    {
        DB::beginTransaction();

        try {
            // إلغاء تفعيل الرسوم السابقة
            StudentFee::where('student_id', $student->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            // إنشاء الرسوم الجديدة
            $fee = StudentFee::create([
                'tenant_id' => $student->tenant_id,
                'student_id' => $student->id,
                'teacher_id' => $feeData['teacher_id'] ?? auth()->id(),
                'monthly_fee' => $feeData['monthly_fee'],
                'currency' => $feeData['currency'] ?? 'SAR',
                'effective_from' => $feeData['effective_from'] ?? Carbon::now()->startOfMonth(),
                'notes' => $feeData['notes'] ?? null,
                'is_active' => true,
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return $fee;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * تحديث رسوم (عن طريق إلغاء القديمة وإنشاء جديدة)
     */
    public function updateStudentFee(StudentFee $fee, array $data): StudentFee
    {
        DB::beginTransaction();

        try {
            // إلغاء الرسوم الحالية
            $fee->update(['is_active' => false]);

            // إنشاء رسوم جديدة
            $newFee = StudentFee::create([
                'tenant_id' => $fee->tenant_id,
                'student_id' => $fee->student_id,
                'teacher_id' => $data['teacher_id'] ?? $fee->teacher_id,
                'monthly_fee' => $data['monthly_fee'],
                'currency' => $data['currency'] ?? $fee->currency,
                'effective_from' => $data['effective_from'] ?? Carbon::now()->startOfMonth(),
                'notes' => $data['notes'] ?? null,
                'is_active' => true,
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return $newFee;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * الحصول على الرسوم النشطة لطالب
     */
    public function getActiveStudentFee(Student $student): ?StudentFee
    {
        return StudentFee::where('student_id', $student->id)
            ->where('is_active', true)
            ->latest('effective_from')
            ->first();
    }

    /**
     * تحديد رسوم جماعية لجميع طلاب فصل
     */
    public function setClassFees(StudyClass $class, float $amount, array $options = []): array
    {
        $students = Student::where('class_id', $class->id)
            ->where('status', 'active')
            ->get();

        $successCount = 0;
        $errors = [];

        foreach ($students as $student) {
            try {
                $this->setStudentFee($student, [
                    'monthly_fee' => $amount,
                    'teacher_id' => $class->teacher_id,
                    'currency' => $options['currency'] ?? 'SAR',
                    'effective_from' => $options['effective_from'] ?? Carbon::now()->startOfMonth(),
                    'notes' => $options['notes'] ?? "رسوم جماعية للفصل: {$class->name}",
                ]);
                $successCount++;
            } catch (\Exception $e) {
                $errors[] = [
                    'student_id' => $student->id,
                    'student_name' => $student->name,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return [
            'total_students' => $students->count(),
            'success_count' => $successCount,
            'error_count' => count($errors),
            'errors' => $errors,
        ];
    }

    /**
     * الحصول على جميع الطلاب الذين لديهم رسوم مع التفاصيل
     */
    public function getStudentsWithFees(int $tenantId, array $filters = [])
    {
        $query = Student::where('students.tenant_id', $tenantId)
            ->with(['activeFee.teacher', 'class']);

        // تطبيق الفلاتر
        if (isset($filters['group'])) {
            $query->where('group', $filters['group']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['has_fee'])) {
            if ($filters['has_fee'] === 'yes') {
                $query->whereHas('activeFee');
            } elseif ($filters['has_fee'] === 'no') {
                $query->whereDoesntHave('activeFee');
            }
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('student_phone', 'like', "%{$search}%")
                  ->orWhere('parent_phone', 'like', "%{$search}%");
            });
        }

        return $query->orderBy('name')->paginate(20);
    }

    /**
     * حذف رسوم (soft delete)
     */
    public function deleteFee(StudentFee $fee): bool
    {
        return $fee->delete();
    }

    /**
     * إحصائيات الرسوم
     */
    public function getFeeStatistics(int $tenantId): array
    {
        $totalStudents = Student::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->count();

        $studentsWithFees = Student::where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->whereHas('activeFee')
            ->count();

        $averageFee = StudentFee::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->avg('monthly_fee');

        $totalMonthlyRevenue = StudentFee::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->sum('monthly_fee');

        return [
            'total_students' => $totalStudents,
            'students_with_fees' => $studentsWithFees,
            'students_without_fees' => $totalStudents - $studentsWithFees,
            'average_fee' => round($averageFee, 2),
            'total_monthly_revenue' => $totalMonthlyRevenue,
            'coverage_rate' => $totalStudents > 0 ? ($studentsWithFees / $totalStudents) * 100 : 0,
        ];
    }
}
