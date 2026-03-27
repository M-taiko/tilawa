<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStudentRequest;
use App\Http\Requests\Admin\UpdateStudentRequest;
use App\Models\Student;
use App\Models\StudyClass;
use App\Services\StudentTransferService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::where('tenant_id', session('current_tenant_id'))
            ->with('class');

        // Apply group filter
        if ($request->filled('group')) {
            $query->where('group', $request->group);
        }

        // Apply track filter
        if ($request->filled('track')) {
            $query->where('track', $request->track);
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // By default, show only active students
            $query->where('status', 'active');
        }

        // Apply search filter
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('parent_name', 'like', '%' . $request->search . '%')
                  ->orWhere('parent_phone', 'like', '%' . $request->search . '%');
            });
        }

        $students = $query->latest()->paginate(20)->withQueryString();

        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        $classes = StudyClass::where('tenant_id', session('current_tenant_id'))
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $surahs = \App\Models\Surah::orderBy('id')->get();

        $foundationSkills = \App\Models\FoundationSkill::where('tenant_id', session('current_tenant_id'))
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('admin.students.create', compact('classes', 'surahs', 'foundationSkills'));
    }

    public function store(StoreStudentRequest $request)
    {
        $validated = $request->validated();

        // Check if tenant has reached student limit
        $tenant = \App\Models\Tenant::find(session('current_tenant_id'));
        if ($tenant && $tenant->hasReachedStudentLimit()) {
            return back()
                ->withErrors(['name' => 'وصل المركز للحد الأقصى من الطلاب (' . $tenant->max_students . ' طالب)'])
                ->withInput();
        }

        if (!empty($validated['class_id'])) {
            $classExists = StudyClass::where('tenant_id', session('current_tenant_id'))
                ->where('id', $validated['class_id'])
                ->where('is_active', true)
                ->exists();

            if (!$classExists) {
                return back()->withErrors(['class_id' => 'Invalid class selection.'])->withInput();
            }
        }

        $validated['tenant_id'] = session('current_tenant_id');
        $validated['status'] = 'active';

        // Generate parent token for kids, or use default for others
        if ($validated['group'] === 'kids') {
            $validated['parent_portal_token'] = bin2hex(random_bytes(32));
        } else {
            // Generate token for men/women but parent link won't work
            $validated['parent_portal_token'] = bin2hex(random_bytes(32));
            // Clear parent fields for non-kids
            $validated['parent_name'] = $validated['parent_name'] ?? 'N/A';
            $validated['parent_phone'] = $validated['parent_phone'] ?? '0000000000';
        }

        $student = Student::create($validated);

        // Auto-create foundation mastery records for ALL active foundation skills
        if ($validated['track'] === 'foundation') {
            $tenantId = session('current_tenant_id');
            $skills = \App\Models\FoundationSkill::where('tenant_id', $tenantId)
                ->where('is_active', true)
                ->get();

            foreach ($skills as $skill) {
                $masteryPercent = $validated['mastery'][$skill->id] ?? 0;
                \App\Models\StudentFoundationSkillMastery::create([
                    'tenant_id' => $tenantId,
                    'student_id' => $student->id,
                    'foundation_skill_id' => $skill->id,
                    'mastery_percent' => $masteryPercent,
                ]);
            }
        }

        return redirect()->route('admin.students.index')->with('success', 'تم إضافة الطالب بنجاح');
    }

    public function edit(Student $student)
    {
        $this->authorizeTenant($student);
        $classes = StudyClass::where('tenant_id', session('current_tenant_id'))
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $surahs = \App\Models\Surah::orderBy('id')->get();

        $foundationSkills = \App\Models\FoundationSkill::where('tenant_id', session('current_tenant_id'))
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Get existing mastery records for this student
        $masteryMap = $student->foundationMastery()
            ->pluck('mastery_percent', 'foundation_skill_id')
            ->toArray();

        return view('admin.students.edit', compact('student', 'classes', 'surahs', 'foundationSkills', 'masteryMap'));
    }

    public function update(UpdateStudentRequest $request, Student $student)
    {
        $this->authorizeTenant($student);

        $validated = $request->validated();

        if (!empty($validated['class_id'])) {
            $classExists = StudyClass::where('tenant_id', session('current_tenant_id'))
                ->where('id', $validated['class_id'])
                ->where('is_active', true)
                ->exists();

            if (!$classExists) {
                return back()->withErrors(['class_id' => 'Invalid class selection.'])->withInput();
            }
        }

        $student->update($validated);

        return redirect()->route('admin.students.index')->with('success', 'تم تحديث بيانات الطالب بنجاح');
    }

    public function destroy(Student $student)
    {
        $this->authorizeTenant($student);
        $student->delete();

        return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully');
    }

    public function regenerateToken(Student $student)
    {
        $this->authorizeTenant($student);

        $student->parent_portal_token = bin2hex(random_bytes(32));
        $student->save();

        return redirect()->route('admin.students.edit', $student)->with('success', 'Parent token regenerated');
    }

    public function toggleStatus(Student $student)
    {
        $this->authorizeTenant($student);

        $newStatus = $student->status === 'active' ? 'inactive' : 'active';

        $student->update([
            'status' => $newStatus,
            'class_id' => $newStatus === 'inactive' ? null : $student->class_id,
            'graduation_date' => $newStatus === 'active' ? null : $student->graduation_date,
        ]);

        $message = $newStatus === 'active' ? 'تم تفعيل الطالب بنجاح' : 'تم تعطيل الطالب بنجاح';

        return back()->with('success', $message);
    }

    public function graduate(Request $request, Student $student)
    {
        $this->authorizeTenant($student);

        $validated = $request->validate([
            'graduation_date' => 'required|date',
        ], [
            'graduation_date.required' => 'تاريخ التخرج مطلوب',
            'graduation_date.date' => 'تاريخ التخرج غير صحيح',
        ]);

        $student->update([
            'status' => 'graduated',
            'graduation_date' => $validated['graduation_date'],
            'class_id' => null, // Remove from class
        ]);

        return redirect()->route('admin.students.index')
            ->with('success', 'تم تخريج الطالب بنجاح');
    }

    public function updateMastery(Request $request, Student $student)
    {
        $this->authorizeTenant($student);

        $validated = $request->validate([
            'mastery' => 'nullable|array',
            'mastery.*' => 'nullable|integer|min:0|max:100',
        ]);

        $tenantId = session('current_tenant_id');

        // Get all active foundation skills for this tenant
        $foundationSkills = \App\Models\FoundationSkill::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->pluck('id');

        foreach ($validated['mastery'] ?? [] as $skillId => $percent) {
            // Verify the skill belongs to the current tenant
            if (!$foundationSkills->contains($skillId)) {
                continue;
            }

            // Update or create mastery record
            \App\Models\StudentFoundationSkillMastery::updateOrCreate(
                [
                    'tenant_id' => $tenantId,
                    'student_id' => $student->id,
                    'foundation_skill_id' => $skillId,
                ],
                [
                    'mastery_percent' => $percent ?? 0,
                ]
            );
        }

        return redirect()->route('admin.students.edit', $student)->with('success', 'Foundation skills mastery updated');
    }

    public function showTransferForm(Student $student)
    {
        $this->authorizeTenant($student);

        $classes = StudyClass::where('tenant_id', session('current_tenant_id'))
            ->where('is_active', true)
            ->with('teacher')
            ->get();

        return view('admin.students.transfer', compact('student', 'classes'));
    }

    public function transfer(Request $request, Student $student, StudentTransferService $transferService)
    {
        $this->authorizeTenant($student);

        $validated = $request->validate([
            'to_class_id' => 'nullable|exists:classes,id',
            'reason' => 'required|string|max:500',
            'notes' => 'nullable|string',
        ]);

        // Verify the new class belongs to the current tenant
        if (!empty($validated['to_class_id'])) {
            $classExists = StudyClass::where('tenant_id', session('current_tenant_id'))
                ->where('id', $validated['to_class_id'])
                ->where('is_active', true)
                ->exists();

            if (!$classExists) {
                return back()->withErrors(['to_class_id' => 'Invalid class selection.'])->withInput();
            }
        }

        $transferService->transferStudent(
            $student,
            $validated['to_class_id'] ?? null,
            $validated['reason'],
            $validated['notes'] ?? null
        );

        return redirect()->route('admin.students.index')
            ->with('success', 'تم نقل الطالب بنجاح');
    }

    public function transferHistory(Student $student, StudentTransferService $transferService)
    {
        $this->authorizeTenant($student);

        $transfers = $transferService->getTransferHistory($student);

        return view('admin.students.transfer-history', compact('student', 'transfers'));
    }

    private function authorizeTenant(Student $student): void
    {
        if ($student->tenant_id !== session('current_tenant_id')) {
            abort(403, 'Unauthorized');
        }
    }
}
