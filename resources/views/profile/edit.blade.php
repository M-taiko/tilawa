@extends('layouts.app')

@section('title', 'الملف الشخصي')

@section('content')
{{-- Page Header --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
 <div>
 <h1 class="text-3xl font-bold text-gray-900">الملف الشخصي</h1>
 <p class="text-sm text-gray-600 mt-1">إدارة معلوماتك الشخصية وكلمة المرور</p>
 </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
 {{-- Profile Information --}}
 <x-card>
 <x-card-header>
 <div class="flex items-center gap-3">
 <div class="p-2 rounded-lg bg-primary-100 text-primary-600">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
 </svg>
 </div>
 <div>
 <h3 class="font-semibold text-gray-900">المعلومات الشخصية</h3>
 <p class="text-xs text-gray-600">تحديث الاسم والبريد الإلكتروني</p>
 </div>
 </div>
 </x-card-header>
 <x-card-body>
 <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
 @csrf
 @method('PUT')

 <x-input
 name="name"
 label="الاسم"
 type="text"
 :value="old('name', $user->name)"
 required
 :error="$errors->first('name')"
 >
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
 </svg>
 </x-slot>
 </x-input>

 <x-input
 name="email"
 label="البريد الإلكتروني"
 type="email"
 :value="old('email', $user->email)"
 required
 :error="$errors->first('email')"
 >
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
 </svg>
 </x-slot>
 </x-input>

 <div class="pt-4">
 <x-button type="submit" variant="primary">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
 </svg>
 حفظ التغييرات
 </x-button>
 </div>
 </form>
 </x-card-body>
 </x-card>

 {{-- Change Password --}}
 <x-card>
 <x-card-header>
 <div class="flex items-center gap-3">
 <div class="p-2 rounded-lg bg-red-100 text-red-600">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
 </svg>
 </div>
 <div>
 <h3 class="font-semibold text-gray-900">تغيير كلمة المرور</h3>
 <p class="text-xs text-gray-600">تحديث كلمة المرور الخاصة بك</p>
 </div>
 </div>
 </x-card-header>
 <x-card-body>
 <form method="POST" action="{{ route('profile.password') }}" class="space-y-4">
 @csrf
 @method('PUT')

 <x-input
 name="current_password"
 label="كلمة المرور الحالية"
 type="password"
 required
 :error="$errors->first('current_password')"
 >
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
 </svg>
 </x-slot>
 </x-input>

 <x-input
 name="new_password"
 label="كلمة المرور الجديدة"
 type="password"
 required
 :error="$errors->first('new_password')"
 >
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
 </svg>
 </x-slot>
 </x-input>

 <x-input
 name="new_password_confirmation"
 label="تأكيد كلمة المرور الجديدة"
 type="password"
 required
 >
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </x-slot>
 </x-input>

 <div class="pt-4">
 <x-button type="submit" variant="danger">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
 </svg>
 تغيير كلمة المرور
 </x-button>
 </div>
 </form>
 </x-card-body>
 </x-card>
</div>

@if(session('success'))
 <script>
 // Auto-hide success message after 3 seconds
 setTimeout(() => {
 const alert = document.querySelector('.alert-success');
 if (alert) alert.remove();
 }, 3000);
 </script>
@endif
@endsection
