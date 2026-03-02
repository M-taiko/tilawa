<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentFee;
use App\Models\StudyClass;
use App\Services\FeeManagementService;
use Illuminate\Http\Request;

class StudentFeeController extends Controller
{
    protected $feeService;

    public function __construct(FeeManagementService $feeService)
    {
        $this->feeService = $feeService;
    }

    /**
     * عرض قائمة الطلاب مع رسومهم
     */
    public function index(Request $request)
    {
        $tenantId = session('current_tenant_id');

        $students = $this->feeService->getStudentsWithFees($tenantId, [
            'group' => $request->group,
            'status' => $request->status ?? 'active',
            'has_fee' => $request->has_fee,
            'search' => $request->search,
        ]);

        $statistics = $this->feeService->getFeeStatistics($tenantId);

        return view('admin.student-fees.index', compact('students', 'statistics'));
    }

    /**
     * عرض نموذج تحديد رسوم
     */
    public function create(Request $request)
    {
        $tenantId = session('current_tenant_id');

        $student = null;
        if ($request->student_id) {
            $student = Student::where('tenant_id', $tenantId)
                ->where('id', $request->student_id)
                ->first();
        }

        $classes = StudyClass::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.student-fees.create', compact('student', 'classes'));
    }

    /**
     * حفظ الرسوم
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'monthly_fee' => 'required|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'effective_from' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $student = Student::findOrFail($validated['student_id']);

        try {
            $this->feeService->setStudentFee($student, $validated);

            return redirect()
                ->route('admin.student-fees.index')
                ->with('success', 'تم تحديد الرسوم بنجاح');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديد الرسوم: ' . $e->getMessage());
        }
    }

    /**
     * عرض نموذج تعديل الرسوم
     */
    public function edit(StudentFee $studentFee)
    {
        $studentFee->load('student', 'teacher');

        return view('admin.student-fees.edit', compact('studentFee'));
    }

    /**
     * تحديث الرسوم
     */
    public function update(Request $request, StudentFee $studentFee)
    {
        $validated = $request->validate([
            'monthly_fee' => 'required|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'effective_from' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $this->feeService->updateStudentFee($studentFee, $validated);

            return redirect()
                ->route('admin.student-fees.index')
                ->with('success', 'تم تحديث الرسوم بنجاح');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث الرسوم: ' . $e->getMessage());
        }
    }

    /**
     * حذف الرسوم
     */
    public function destroy(StudentFee $studentFee)
    {
        try {
            $this->feeService->deleteFee($studentFee);

            return redirect()
                ->route('admin.student-fees.index')
                ->with('success', 'تم حذف الرسوم بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء حذف الرسوم: ' . $e->getMessage());
        }
    }

    /**
     * تحديد رسوم جماعية لفصل كامل
     */
    public function bulkSet(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'monthly_fee' => 'required|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'effective_from' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $class = StudyClass::findOrFail($validated['class_id']);

        try {
            $result = $this->feeService->setClassFees($class, $validated['monthly_fee'], [
                'currency' => $validated['currency'] ?? 'SAR',
                'effective_from' => $validated['effective_from'] ?? now(),
                'notes' => $validated['notes'],
            ]);

            $message = sprintf(
                'تم تحديد الرسوم لـ %d طالب من أصل %d',
                $result['success_count'],
                $result['total_students']
            );

            if ($result['error_count'] > 0) {
                $message .= sprintf(' (فشل %d)', $result['error_count']);
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }
}
