@extends('layouts.app')

@section('title', 'تعديل معلم')

@section('content')
{{-- Page Header --}}
<div class="mb-8">
 <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
 <div>
 <h1 class="text-3xl font-bold text-gray-900">تعديل معلم</h1>
 <p class="text-sm text-gray-500 mt-1">تحديث بيانات المعلم <span class="font-semibold text-gray-900">{{ $teacher->name }}</span></p>
 </div>
 <x-button href="{{ route('admin.teachers.index') }}" variant="ghost">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
 </svg>
 رجوع
 </x-button>
 </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
 {{-- Edit Form --}}
 <div class="lg:col-span-2">
 <x-card class="bg-white border border-gray-200 rounded-xl shadow-sm">
 <x-card-body class="p-6">
 <form method="POST" action="{{ route('admin.teachers.update', $teacher) }}" class="space-y-8">
 @csrf
 @method('PUT')

 {{-- Section 1: Personal Information --}}
 <div class="space-y-6">
 <div class="flex items-center gap-3 pb-3 border-b border-gray-200">
 <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50">
 <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
 </svg>
 </div>
 <div>
 <h2 class="text-lg font-bold text-gray-900">معلومات المعلم</h2>
 <p class="text-sm text-gray-500">البيانات الأساسية للمعلم</p>
 </div>
 </div>

 {{-- Teacher Name --}}
 <div>
 <x-input
 name="name"
 label="اسم المعلم"
 type="text"
 placeholder="أدخل اسم المعلم"
 :value="old('name', $teacher->name)"
 required
 :error="$errors->first('name')"
 >
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
 </svg>
 </x-slot>
 </x-input>
 </div>
 </div>

 {{-- Section 2: Account Information --}}
 <div class="space-y-6">
 <div class="flex items-center gap-3 pb-3 border-b border-gray-200">
 <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50">
 <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
 </svg>
 </div>
 <div>
 <h2 class="text-lg font-bold text-gray-900">معلومات تسجيل الدخول</h2>
 <p class="text-sm text-gray-500">البريد الإلكتروني وكلمة المرور</p>
 </div>
 </div>

 {{-- Email & Password --}}
 <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
 <x-input
 name="email"
 label="البريد الإلكتروني"
 type="email"
 placeholder="example@domain.com"
 :value="old('email', $teacher->email)"
 required
 :error="$errors->first('email')"
 >
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
 </svg>
 </x-slot>
 </x-input>

 <x-input
 name="password"
 label="كلمة مرور جديدة (اختياري)"
 type="password"
 placeholder="اتركه فارغًا إذا لم ترد التغيير"
 :error="$errors->first('password')"
 helper="اترك هذا الحقل فارغًا إذا كنت لا تريد تغيير كلمة المرور"
 >
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
 </svg>
 </x-slot>
 </x-input>
 </div>
 </div>

 {{-- Section 3: Permissions --}}
 <div class="space-y-6">
 <div class="flex items-center gap-3 pb-3 border-b border-gray-200">
 <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50">
 <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
 </svg>
 </div>
 <div>
 <h2 class="text-lg font-bold text-gray-900">صلاحيات الوصول</h2>
 <p class="text-sm text-gray-500">المجموعات المسموح للمعلم الوصول إليها</p>
 </div>
 </div>

 {{-- Allowed Groups --}}
 <div>
 <label class="block text-sm font-medium text-gray-700 mb-2">
 <span class="flex items-center gap-2">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
 </svg>
 المجموعات المسموحة
 </span>
 </label>
 <select
 name="allowed_groups[]"
 multiple
 class="w-full pr-4 pl-12 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22M6%208l4%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.5rem] bg-[center_left_0.5rem] bg-no-repeat"
 >
 @foreach ($groups as $group)
 <option value="{{ $group }}" @if(in_array($group, $membership->allowed_groups_json ?? [])) selected @endif>{{ $group }}</option>
 @endforeach
 </select>
 <p class="text-xs text-gray-500 mt-2">
 <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 المجموعات تُنشأ عند إضافة حلقة جديدة.
 </p>
 @error('allowed_groups')
 <p class="text-xs text-rose-600 mt-1.5 flex items-center gap-1">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 {{ $message }}
 </p>
 @enderror
 </div>
 </div>

 {{-- Form Actions --}}
 <div class="flex flex-col sm:flex-row items-center gap-3 pt-6 border-t border-gray-200">
 <x-button type="submit" variant="primary" size="lg" class="w-full sm:w-auto">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
 </svg>
 تحديث البيانات
 </x-button>
 <x-button href="{{ route('admin.teachers.index') }}" variant="outline" size="lg" class="w-full sm:w-auto">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
 </svg>
 إلغاء
 </x-button>
 </div>
 </form>
 </x-card-body>
 </x-card>
 </div>

 {{-- Sidebar --}}
 <div class="lg:col-span-1 space-y-6">
 {{-- Teacher Status Management --}}
 <x-card class="bg-white border border-gray-200 rounded-xl shadow-sm">
 <x-card-header class="bg-gray-50 border-b border-gray-200">
 <div class="flex items-center gap-3">
 <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-100">
 <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </div>
 <div>
 <h3 class="font-bold text-gray-900">حالة المعلم</h3>
 <p class="text-xs text-gray-600">إدارة حالة حساب المعلم</p>
 </div>
 </div>
 </x-card-header>
 <x-card-body class="space-y-4">
 {{-- Current Status Badge --}}
 <div>
 <label class="block text-xs font-semibold text-gray-700 mb-2">الحالة الحالية</label>
 <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold
 @if($teacher->is_active) bg-green-600 text-white
 @else bg-red-600 text-white @endif">
 @if($teacher->is_active)
 نشط
 @else
 غير نشط
 @endif
 </div>
 </div>

 {{-- Toggle Status Button --}}
 <div class="border-t border-gray-200 pt-4">
 <form method="POST" action="{{ route('admin.teachers.toggle-status', $teacher) }}">
 @csrf
 <x-button
 type="submit"
 variant="{{ $teacher->is_active ? 'warning' : 'primary' }}"
 class="w-full justify-center"
 >
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 @if($teacher->is_active)
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
 @else
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 @endif
 </svg>
 {{ $teacher->is_active ? 'تعطيل المعلم' : 'تفعيل المعلم' }}
 </x-button>
 </form>
 </div>
 </x-card-body>
 </x-card>
 </div>
</div>
@endsection
