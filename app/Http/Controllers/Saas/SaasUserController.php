<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SaasUserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::with('tenants')
            ->when($search, fn($q) => $q->where('name', 'like', "%$search%")
                                        ->orWhere('email', 'like', "%$search%"))
            ->orderByDesc('created_at')
            ->paginate(30)
            ->withQueryString();

        return view('saas.users.index', compact('users', 'search'));
    }

    public function edit(User $user)
    {
        $user->load('tenants');
        return view('saas.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password'   => 'nullable|string|min:6|confirmed',
            'is_active'  => 'boolean',
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $data['is_active'] = $request->boolean('is_active');

        $user->update($data);

        return redirect()->route('saas.users.index')
            ->with('success', 'تم تحديث بيانات المستخدم بنجاح.');
    }
}
