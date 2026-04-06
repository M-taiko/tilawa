@extends('layouts.app')

@section('title', 'تعديل مستخدم — ' . $user->name)
@section('noindex')@endsection

@section('content')
{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">تعديل بيانات المستخدم</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $user->email }}</p>
    </div>
    <x-button href="{{ route('saas.users.index') }}" variant="ghost">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        رجوع
    </x-button>
</div>

<div class="max-w-2xl">
    <x-card>
        <form method="POST" action="{{ route('saas.users.update', $user) }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            {{-- Name --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">الاسم</label>
                <x-input name="name" value="{{ old('name', $user->name) }}" required />
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">البريد الإلكتروني</label>
                <x-input name="email" type="email" value="{{ old('email', $user->email) }}" required />
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    كلمة المرور الجديدة
                    <span class="font-normal text-gray-400">(اتركها فارغة إذا لا تريد تغييرها)</span>
                </label>
                <x-input name="password" type="password" autocomplete="new-password" />
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Confirm Password --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">تأكيد كلمة المرور</label>
                <x-input name="password_confirmation" type="password" autocomplete="new-password" />
            </div>

            {{-- Status --}}
            <div>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1"
                           class="w-5 h-5 rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                           {{ $user->is_active ? 'checked' : '' }}>
                    <span class="text-sm font-semibold text-gray-700">الحساب نشط</span>
                </label>
            </div>

            {{-- Tenant Info (Read Only) --}}
            @if($user->tenants->isNotEmpty())
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-sm font-semibold text-gray-600 mb-3">المراكز المرتبط بها</p>
                <div class="space-y-2">
                    @foreach($user->tenants as $tenant)
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-700">{{ $tenant->name }}</span>
                        @php $role = $tenant->pivot->role; @endphp
                        @if($role === 'tenant_admin')
                            <x-badge variant="info">مدير مركز</x-badge>
                        @elseif($role === 'teacher')
                            <x-badge variant="success">معلم</x-badge>
                        @else
                            <x-badge>{{ $role }}</x-badge>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Submit --}}
            <div class="flex gap-3 pt-2">
                <x-button type="submit" variant="primary" class="flex-1">
                    حفظ التغييرات
                </x-button>
                <x-button href="{{ route('saas.users.index') }}" variant="ghost">
                    إلغاء
                </x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection
