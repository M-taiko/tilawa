<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudyClass;
use App\Models\TenantUser;
use App\Models\User;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $query = StudyClass::where('tenant_id', session('current_tenant_id'))
            ->with('teacher')
            ->withCount('students');

        // Apply status filter
        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        // Apply group filter
        if ($request->filled('group')) {
            $query->where('group', $request->group);
        }

        // Apply track filter
        if ($request->filled('track')) {
            $query->where('track', $request->track);
        }

        // Apply search filter
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $classes = $query->latest()->paginate(20)->withQueryString();

        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        $teachers = $this->tenantTeachers();
        return view('admin.classes.create', compact('teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'group' => 'required|in:men,women,kids',
            'track' => 'required|in:memorization,foundation',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        $teacherId = $validated['teacher_id'] ?? null;
        if ($teacherId) {
            $membership = TenantUser::where('tenant_id', session('current_tenant_id'))
                ->where('user_id', $teacherId)
                ->where('role', 'teacher')
                ->first();

            if (!$membership) {
                return back()->withErrors(['teacher_id' => 'المعلم غير مرتبط بهذا المركز.'])->withInput();
            }

            $allowed = $membership->allowed_groups_json ?? [];
            if (!in_array($validated['group'], $allowed, true)) {
                return back()->withErrors(['teacher_id' => 'المعلم غير مسموح له بهذه المجموعة.'])->withInput();
            }
        }

        StudyClass::create([
            'tenant_id' => session('current_tenant_id'),
            'name' => $validated['name'],
            'group' => $validated['group'],
            'track' => $validated['track'],
            'teacher_id' => $teacherId,
        ]);

        return redirect()->route('admin.classes.index')->with('success', 'تم إنشاء الحلقة');
    }

    public function edit(StudyClass $class)
    {
        $this->authorizeTenant($class);
        $class->loadCount('students');

        $teachers = $this->tenantTeachers();
        return view('admin.classes.edit', compact('class', 'teachers'));
    }

    public function update(Request $request, StudyClass $class)
    {
        $this->authorizeTenant($class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'group' => 'required|in:men,women,kids',
            'track' => 'required|in:memorization,foundation',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        $teacherId = $validated['teacher_id'] ?? null;
        if ($teacherId) {
            $membership = TenantUser::where('tenant_id', session('current_tenant_id'))
                ->where('user_id', $teacherId)
                ->where('role', 'teacher')
                ->first();

            if (!$membership) {
                return back()->withErrors(['teacher_id' => 'المعلم غير مرتبط بهذا المركز.'])->withInput();
            }

            $allowed = $membership->allowed_groups_json ?? [];
            if (!in_array($validated['group'], $allowed, true)) {
                return back()->withErrors(['teacher_id' => 'المعلم غير مسموح له بهذه المجموعة.'])->withInput();
            }
        }

        $class->update([
            'name' => $validated['name'],
            'group' => $validated['group'],
            'track' => $validated['track'],
            'teacher_id' => $teacherId,
        ]);

        return redirect()->route('admin.classes.index')->with('success', 'تم تحديث الحلقة');
    }

    public function destroy(StudyClass $class)
    {
        $this->authorizeTenant($class);
        $class->delete();

        return redirect()->route('admin.classes.index')->with('success', 'تم حذف الحلقة');
    }

    public function toggleStatus(StudyClass $class)
    {
        $this->authorizeTenant($class);

        $class->update([
            'is_active' => !$class->is_active,
        ]);

        $message = $class->is_active ? 'تم تفعيل الحلقة بنجاح' : 'تم تعطيل الحلقة بنجاح';

        return back()->with('success', $message);
    }

    private function authorizeTenant(StudyClass $class): void
    {
        if ($class->tenant_id !== session('current_tenant_id')) {
            abort(403, 'Unauthorized');
        }
    }

    private function tenantTeachers()
    {
        $tenantId = session('current_tenant_id');
        return User::whereHas('tenants', function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId)->where('role', 'teacher');
        })
        ->where('is_active', true)
        ->orderBy('name')
        ->get();
    }
}
