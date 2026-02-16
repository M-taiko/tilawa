<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\TenantUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::latest()->paginate(20);
        return view('saas.tenants.index', compact('tenants'));
    }

    public function create()
    {
        return view('saas.tenants.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_name' => 'required|string|max:255',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255|unique:users,email',
            'admin_password' => 'required|string|min:6',
        ]);

        $tenant = Tenant::create([
            'name' => $validated['tenant_name'],
        ]);

        $admin = User::create([
            'name' => $validated['admin_name'],
            'email' => $validated['admin_email'],
            'password' => Hash::make($validated['admin_password']),
            'global_role' => 'tenant_admin',
        ]);

        TenantUser::create([
            'tenant_id' => $tenant->id,
            'user_id' => $admin->id,
            'role' => 'tenant_admin',
        ]);

        return redirect()->route('saas.tenants.index')->with('success', 'تم إنشاء المركز بنجاح');
    }

    public function edit(Tenant $tenant)
    {
        return view('saas.tenants.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'tenant_name' => 'required|string|max:255',
        ]);

        $tenant->update([
            'name' => $validated['tenant_name'],
        ]);

        return redirect()->route('saas.tenants.index')->with('success', 'تم تحديث بيانات المركز');
    }

    public function toggleStatus(Tenant $tenant)
    {
        $tenant->update([
            'is_active' => !$tenant->is_active,
        ]);

        if (!$tenant->is_active) {
            // Deactivate all users in this tenant
            User::whereHas('tenants', function ($query) use ($tenant) {
                $query->where('tenant_id', $tenant->id);
            })->update(['is_active' => false]);
        } else {
            // Reactivate tenant admins when tenant is enabled
            $adminIds = TenantUser::where('tenant_id', $tenant->id)
                ->where('role', 'tenant_admin')
                ->pluck('user_id');

            User::whereIn('id', $adminIds)->update(['is_active' => true]);
        }

        $message = $tenant->is_active ? 'تم تفعيل المركز وإعادة تفعيل المديرين' : 'تم تعطيل المركز وجميع المستخدمين';

        return back()->with('success', $message);
    }

    public function destroy(Tenant $tenant)
    {
        // This will cascade delete all related data
        $tenant->delete();

        return redirect()->route('saas.tenants.index')->with('success', 'تم حذف المركز وجميع بياناته');
    }
}
