<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $credentials['login'])->first();

        // Check if user is active
        if ($user && !$user->is_active) {
            return back()->withErrors(['login' => 'هذا الحساب غير نشط. يرجى التواصل مع الإدارة.']);
        }

        if ($user && Auth::attempt(['email' => $user->email, 'password' => $credentials['password']])) {
            $request->session()->regenerate();

            if ($user->isSaasAdmin()) {
                return redirect()->route('saas.tenants.index');
            }

            $tenantId = $user->tenants()->pluck('tenants.id')->first();
            if (!$tenantId) {
                Auth::logout();
                return redirect()->route('login')->withErrors(['login' => 'لا يوجد مركز مرتبط بهذا الحساب.']);
            }

            session(['current_tenant_id' => $tenantId]);

            $tenantRole = $user->tenants()->where('tenants.id', $tenantId)->first()?->pivot?->role;
            if ($tenantRole === 'tenant_admin') {
                return redirect()->route('admin.dashboard');
            }

            if ($tenantRole === 'teacher') {
                return redirect()->route('teacher.dashboard');
            }

            Auth::logout();
            return redirect()->route('login')->withErrors(['login' => 'Unauthorized role.']);
        }

        return back()->withErrors(['login' => 'بيانات الدخول غير صحيحة.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
