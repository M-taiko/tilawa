@extends('layouts.app')

@section('title', 'إضافة مدير جديد')

@section('content')
{{-- Page Header --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
 <div>
 <h1 class="text-3xl font-bold text-gray-900">إضافة مدير جديد</h1>
 <p class="text-sm text-gray-600 mt-1">إضافة مدير جديد لمركز: {{ $tenant->name }}</p>
 </div>
 <x-button href="{{ route('saas.tenant_admins.index', $tenant) }}" variant="ghost">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
 </svg>
 رجوع
 </x-button>
</div>

<x-card>
 <x-card-body>
 <form method="POST" action="{{ route('saas.tenant_admins.store', $tenant) }}" class="space-y-6">
 @csrf

 {{-- Name --}}
 <x-input name="name" label="الاسم الكامل" required>
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
 </svg>
 </x-slot>
 </x-input>

 <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
 {{-- Username --}}

 {{-- Email --}}
 <x-input name="email" label="البريد الإلكتروني" type="email" required>
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
 </svg>
 </x-slot>
 </x-input>
 </div>

 {{-- Password --}}
 <x-input name="password" label="كلمة المرور" type="password" required>
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
 </svg>
 </x-slot>
 <x-slot name="help">
 يجب أن تكون كلمة المرور 6 أحرف على الأقل
 </x-slot>
 </x-input>

 {{-- Form Actions --}}
 <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
 <x-button type="submit" variant="primary" size="lg">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
 </svg>
 إضافة المدير
 </x-button>
 <x-button href="{{ route('saas.tenant_admins.index', $tenant) }}" variant="outline" size="lg">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
 </svg>
 إلغاء
 </x-button>
 </div>
 </form>
 </x-card-body>
</x-card>
@endsection
