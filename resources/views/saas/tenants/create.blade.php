@extends('layouts.app')

@section('title', 'مركز جديد')

@section('content')
{{-- Page Header --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
 <div>
 <h1 class="text-3xl font-bold text-gray-900">إنشاء مركز جديد</h1>
 <p class="text-sm text-gray-600 mt-1">إضافة مركز تحفيظ جديد للنظام</p>
 </div>
 <x-button href="{{ route('saas.tenants.index') }}" variant="ghost">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
 </svg>
 رجوع
 </x-button>
</div>

<x-card>
 <x-card-body>
 <form method="POST" action="{{ route('saas.tenants.store') }}" class="space-y-6">
 @csrf
 
 {{-- Tenant Info --}}
 <div>
 <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">بيانات المركز</h3>
 <x-input name="tenant_name" label="اسم المركز" placeholder="مثال: مركز النور لتحفيظ القرآن" required value="{{ old('tenant_name') }}">
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
 </svg>
 </x-slot>
 </x-input>

 {{-- Max Teachers & Students --}}
 <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
 <x-input name="max_teachers" label="الحد الأقصى للمعلمين" type="number"
 placeholder="مثال: 10" required value="{{ old('max_teachers', 10) }}"
 :error="$errors->first('max_teachers')"
 helper="عدد المعلمين المسموح بتسجيلهم" min="1">
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
 d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
 </svg>
 </x-slot>
 </x-input>
 <x-input name="max_students" label="الحد الأقصى للطلاب" type="number"
 placeholder="مثال: 100" required value="{{ old('max_students', 100) }}"
 :error="$errors->first('max_students')"
 helper="عدد الطلاب المسموح بتسجيلهم" min="1">
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
 d="M12 6.253v13m0-13C6.596.75 2.247 2.506 2.247 5.08c0 3.577 3.066 5.932 9.753 12.745m0-13c5.404-3.874 9.753-2.118 9.753.665 0 3.577-3.066 5.932-9.753 12.745"/>
 </svg>
 </x-slot>
 </x-input>
 </div>
 </div>

 {{-- Admin Info --}}
 <div>
 <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">بيانات المدير المسؤول</h3>
 <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
 <x-input name="admin_name" label="الاسم الكامل" placeholder="الاسم الكامل للمدير" required value="{{ old('admin_name') }}">
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
 </svg>
 </x-slot>
 </x-input>


 <x-input name="admin_email" label="البريد الإلكتروني" type="email" placeholder="example@domain.com" required value="{{ old('admin_email') }}">
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
 </svg>
 </x-slot>
 </x-input>

 <x-input name="admin_password" label="كلمة المرور" type="password" placeholder="********" required>
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
 </svg>
 </x-slot>
 </x-input>
 </div>
 </div>

 <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
 <x-button type="submit" variant="primary" size="lg">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
 </svg>
 إنشاء المركز
 </x-button>
 <x-button href="{{ route('saas.tenants.index') }}" variant="outline" size="lg">
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
