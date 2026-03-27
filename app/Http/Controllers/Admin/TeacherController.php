<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTeacherRequest;
use App\Http\Requests\Admin\UpdateTeacherRequest;
use App\Models\TenantUser;
use App\Models\User;
use App\Services\TeacherWorkloadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = session('current_tenant_id');

        $query = User::whereHas('tenants', function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId)->where('role', 'teacher');
        })
            ->with(['tenants' => function ($query) use ($tenantId) {
                $query->where('tenant_id', $tenantId);
            }]);

        // Apply status filter
        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        // Apply search filter
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $teachers = $query->latest()->paginate(20)->withQueryString();

        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        $groups = $this->getGroups();
        return view('admin.teachers.create', compact('groups'));
    }

    public function store(StoreTeacherRequest $request)
    {
        $validated = $request->validated();

        // Check if tenant has reached teacher limit
        $tenant = \App\Models\Tenant::find(session('current_tenant_id'));
        if ($tenant && $tenant->hasReachedTeacherLimit()) {
            return back()
                ->withErrors(['email' => 'وصل المركز للحد الأقصى من المعلمين (' . $tenant->max_teachers . ' معلم)'])
                ->withInput();
        }

        $existingUser = User::where('email', $validated['email'])->first();

        if ($existingUser) {
            $already = TenantUser::where('tenant_id', session('current_tenant_id'))
                ->where('user_id', $existingUser->id)
                ->exists();

            if ($already) {
                return back()->withErrors(['email' => 'هذا المعلم مرتبط بهذا المركز مسبقاً.'])->withInput();
            }

            $existingUser->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $user = $existingUser;
        } else {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'global_role' => 'teacher',
            ]);
        }

        TenantUser::create([
            'tenant_id' => session('current_tenant_id'),
            'user_id' => $user->id,
            'role' => 'teacher',
            'allowed_groups_json' => array_values(array_filter($validated['allowed_groups'] ?? [])),
        ]);

        return redirect()->route('admin.teachers.index')->with('success', 'تم إنشاء المعلم بنجاح');
    }

    public function edit(User $teacher)
    {
        $membership = $this->authorizeTenantTeacher($teacher);
        $groups = $this->getGroups();

        return view('admin.teachers.edit', compact('teacher', 'groups', 'membership'));
    }

    public function update(UpdateTeacherRequest $request, User $teacher)
    {
        $membership = $this->authorizeTenantTeacher($teacher);

        $validated = $request->validated();

        $teacher->name = $validated['name'];
        $teacher->email = $validated['email'];

        if ($request->filled('password')) {
            $teacher->password = Hash::make($validated['password']);
        }

        $teacher->save();

        $membership->allowed_groups_json = array_values(array_filter($validated['allowed_groups'] ?? []));
        $membership->save();

        return redirect()->route('admin.teachers.index')->with('success', 'تم تحديث المعلم');
    }

    public function destroy(User $teacher)
    {
        $membership = $this->authorizeTenantTeacher($teacher);
        $membership->delete();

        return redirect()->route('admin.teachers.index')->with('success', 'تم حذف المعلم من هذا المركز');
    }

    public function toggleStatus(User $teacher)
    {
        $this->authorizeTenantTeacher($teacher);

        $teacher->update([
            'is_active' => !$teacher->is_active,
        ]);

        $message = $teacher->is_active ? 'تم تفعيل المعلم بنجاح' : 'تم تعطيل المعلم بنجاح';

        return back()->with('success', $message);
    }

    public function workload(TeacherWorkloadService $workloadService)
    {
        $tenantId = session('current_tenant_id');
        $workloads = $workloadService->getAllTeachersWorkload($tenantId);

        // Calculate summary statistics
        $summary = [
            'total_teachers' => count($workloads),
            'overloaded' => collect($workloads)->where('workload.workload_level', 'overloaded')->count(),
            'high_workload' => collect($workloads)->where('workload.workload_level', 'high')->count(),
            'normal_workload' => collect($workloads)->where('workload.workload_level', 'normal')->count(),
            'low_workload' => collect($workloads)->where('workload.workload_level', 'low')->count(),
        ];

        return view('admin.teachers.workload', compact('workloads', 'summary'));
    }

    private function authorizeTenantTeacher(User $teacher): TenantUser
    {
        $membership = TenantUser::where('tenant_id', session('current_tenant_id'))
            ->where('user_id', $teacher->id)
            ->where('role', 'teacher')
            ->first();

        if (!$membership) {
            abort(403, 'Unauthorized');
        }

        return $membership;
    }

    private function getGroups()
    {
        return \App\Models\StudyClass::where('tenant_id', session('current_tenant_id'))
            ->select('group')
            ->distinct()
            ->orderBy('group')
            ->pluck('group');
    }
}
