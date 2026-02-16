<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\TenantUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TenantAdminController extends Controller
{
    public function index(Tenant $tenant)
    {
        $admins = TenantUser::where('tenant_id', $tenant->id)
            ->where('role', 'tenant_admin')
            ->with('user')
            ->get();

        return view('saas.tenant_admins.index', compact('tenant', 'admins'));
    }

    public function create(Tenant $tenant)
    {
        return view('saas.tenant_admins.create', compact('tenant'));
    }

    public function store(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'global_role' => 'tenant_admin',
        ]);

        // Attach to tenant as admin
        TenantUser::create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'role' => 'tenant_admin',
        ]);

        return redirect()->route('saas.tenant_admins.index', $tenant)
            ->with('success', 'تم إضافة المدير بنجاح');
    }

    public function edit(Tenant $tenant, User $admin)
    {
        // Verify this user is an admin of this tenant
        $tenantUser = TenantUser::where('tenant_id', $tenant->id)
            ->where('user_id', $admin->id)
            ->where('role', 'tenant_admin')
            ->firstOrFail();

        return view('saas.tenant_admins.edit', compact('tenant', 'admin'));
    }

    public function update(Request $request, Tenant $tenant, User $admin)
    {
        // Verify this user is an admin of this tenant
        $tenantUser = TenantUser::where('tenant_id', $tenant->id)
            ->where('user_id', $admin->id)
            ->where('role', 'tenant_admin')
            ->firstOrFail();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($admin->id)],
            'password' => 'nullable|string|min:6',
        ]);

        $admin->name = $validated['name'];
        $admin->email = $validated['email'];

        if ($request->filled('password')) {
            $admin->password = Hash::make($validated['password']);
        }

        $admin->save();

        return redirect()->route('saas.tenant_admins.index', $tenant)
            ->with('success', 'تم تحديث بيانات المدير بنجاح');
    }

    public function destroy(Tenant $tenant, User $admin)
    {
        // Verify this user is an admin of this tenant
        $tenantUser = TenantUser::where('tenant_id', $tenant->id)
            ->where('user_id', $admin->id)
            ->where('role', 'tenant_admin')
            ->firstOrFail();

        // Check if this is the last admin
        $adminCount = TenantUser::where('tenant_id', $tenant->id)
            ->where('role', 'tenant_admin')
            ->count();

        if ($adminCount <= 1) {
            return back()->withErrors(['error' => 'لا يمكن حذف المدير الوحيد للمركز']);
        }

        // Remove from tenant
        $tenantUser->delete();

        // If user has no other tenants, optionally delete the user
        // (for now, we'll just remove from tenant)

        return redirect()->route('saas.tenant_admins.index', $tenant)
            ->with('success', 'تم حذف المدير بنجاح');
    }

    public function toggleStatus(Tenant $tenant, User $admin)
    {
        // Verify this user is an admin of this tenant
        TenantUser::where('tenant_id', $tenant->id)
            ->where('user_id', $admin->id)
            ->where('role', 'tenant_admin')
            ->firstOrFail();

        $admin->update([
            'is_active' => !$admin->is_active,
        ]);

        $message = $admin->is_active ? 'تم تفعيل المدير بنجاح' : 'تم تعطيل المدير بنجاح';

        return back()->with('success', $message);
    }
}
