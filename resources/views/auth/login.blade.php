@extends('layouts.auth')

@section('title', 'تسجيل الدخول')

@section('content')
<div class="grid lg:grid-cols-2 gap-8 items-center">
 {{-- Login Form Card --}}
 <x-card class="p-8 lg:p-10">
 {{-- Header --}}
 <div class="flex items-center gap-4 mb-6">
 <div class="h-14 w-14 rounded-lg bg-gradient-to-br from-primary-600 to-primary-700 text-white flex items-center justify-center font-bold text-2xl shadow-md shadow-primary-500/30">
 ت
 </div>
 <div>
 <h1 class="text-2xl font-bold text-gray-900">مرحبا بك</h1>
 <p class="text-sm text-gray-600">تسجيل الدخول إلى منصة التحفيظ</p>
 </div>
 </div>

 <p class="text-sm text-gray-700 mb-8">
 أدخل بيانات حسابك للوصول إلى المركز وإدارة الطلاب.
 </p>

 {{-- Login Form --}}
 <form method="POST" action="{{ route('login.submit') }}" class="space-y-5">
 @csrf

 {{-- Email Input --}}
 <x-input
 name="login"
 label="البريد الإلكتروني"
 type="email"
 placeholder="أدخل البريد الإلكتروني"
 :value="old('login')"
 required
 :error="$errors->first('login')"
 >
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
 </svg>
 </x-slot>
 </x-input>

 {{-- Password Input --}}
 <x-input
 name="password"
 label="كلمة المرور"
 type="password"
 placeholder="أدخل كلمة المرور"
 required
 :error="$errors->first('password')"
 >
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
 </svg>
 </x-slot>
 </x-input>

 {{-- Submit Button --}}
 <x-button type="submit" variant="primary" class="w-full justify-center" size="lg">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
 </svg>
 دخول
 </x-button>
 </form>
 </x-card>

 {{-- Features Card (Desktop Only) --}}
 <div class="hidden lg:block">
 <x-card class="p-8 lg:p-10">
 {{-- Title --}}
 <div class="flex items-center gap-3 mb-6">
 <div class="p-3 rounded-lg bg-primary-100 text-primary-600">
 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
 </svg>
 </div>
 <h2 class="text-xl font-bold text-gray-900">لماذا Tilawa؟</h2>
 </div>

 {{-- Features List --}}
 <ul class="space-y-4">
 <li class="flex items-start gap-3 group">
 <div class="flex-shrink-0 p-1.5 rounded-lg bg-success-100 text-success-600">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
 </svg>
 </div>
 <span class="text-sm text-gray-700 leading-relaxed">
 متابعة دقيقة لمسار الحفظ والتقدم
 </span>
 </li>
 <li class="flex items-start gap-3 group">
 <div class="flex-shrink-0 p-1.5 rounded-lg bg-success-100 text-success-600">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
 </svg>
 </div>
 <span class="text-sm text-gray-700 leading-relaxed">
 تقييم واضح وموحد بين المعلمين
 </span>
 </li>
 <li class="flex items-start gap-3 group">
 <div class="flex-shrink-0 p-1.5 rounded-lg bg-success-100 text-success-600">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
 </svg>
 </div>
 <span class="text-sm text-gray-700 leading-relaxed">
 وصول أولياء الأمور لتقارير الطالب
 </span>
 </li>
 </ul>

 </x-card>
 </div>
</div>
@endsection
