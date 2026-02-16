@extends('layouts.app')

@section('title', 'خريطة تقدم الحفظ')

@section('content')
<div class="max-w-7xl mx-auto">
 {{-- Page Header --}}
 <div class="mb-8">
 <x-button
 :href="route('admin.students.index')"
 variant="ghost"
 size="sm"
 class="mb-4 !px-0"
 >
 <svg class="w-4 h-4 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
 </svg>
 رجوع للطلاب
 </x-button>
 <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
 <div>
 <h1 class="text-3xl font-bold text-gray-900">خريطة تقدم الحفظ</h1>
 <div class="flex items-center gap-2 mt-2">
 <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
 </svg>
 <p class="text-sm text-gray-600">{{ $student->name }}</p>
 </div>
 </div>
 <div class="flex items-center gap-2">
 <x-button
 :href="route('teacher.sessions.create', ['student_id' => $student->id])"
 variant="primary"
 size="md"
 >
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
 </svg>
 <span>إضافة جلسة</span>
 </x-button>
 </div>
 </div>
 </div>

 {{-- Overall Statistics --}}
 <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
 <x-hero-stat-card
 label="نسبة الإنجاز"
 :value="number_format($statistics['progress_percent'], 1) . '%'"
 gradient="blue"
 >
 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </x-hero-stat-card>

 <x-gradient-stat-card
 label="آيات محفوظة"
 :value="number_format($statistics['memorized_ayahs'])"
 gradient="green"
 >
 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
 </svg>
 </x-gradient-stat-card>

 <x-gradient-stat-card
 label="سور مكتملة"
 :value="$statistics['completed_surahs'] . '/114'"
 gradient="purple"
 >
 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
 </svg>
 </x-gradient-stat-card>

 <x-gradient-stat-card
 label="آيات متبقية"
 :value="number_format($statistics['remaining_ayahs'])"
 gradient="yellow"
 >
 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
 </svg>
 </x-gradient-stat-card>
 </div>

 {{-- Legend Card --}}
 <x-card class="mb-6">
 <div class="flex flex-wrap items-center gap-4 sm:gap-6">
 <div class="flex items-center gap-2">
 <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 <span class="text-sm font-medium text-gray-700">الدلالات:</span>
 </div>
 <div class="flex items-center gap-2 px-3 py-1.5 bg-green-50 border border-green-200 rounded-lg">
 <div class="w-3 h-3 rounded-full bg-green-500"></div>
 <span class="text-sm text-green-800 font-medium">مكتمل</span>
 </div>
 <div class="flex items-center gap-2 px-3 py-1.5 bg-blue-50 border border-blue-200 rounded-lg">
 <div class="w-3 h-3 rounded-full bg-blue-500"></div>
 <span class="text-sm text-blue-800 font-medium">قيد الحفظ</span>
 </div>
 <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-100 border border-gray-200 rounded-lg">
 <div class="w-3 h-3 rounded-full bg-gray-400"></div>
 <span class="text-sm text-gray-700 font-medium">لم يبدأ</span>
 </div>
 </div>
 </x-card>

 {{-- Progress Map Grid --}}
 <x-card class="mb-6">
 <div class="flex items-center justify-between mb-6">
 <h2 class="text-xl font-bold text-gray-900">سور القرآن الكريم</h2>
 <div class="flex items-center gap-2 text-sm text-gray-500">
 <span class="w-2 h-2 bg-green-500 rounded-full"></span>
 <span>{{ collect($progressMap)->where('status', 'completed')->count() }} سورة مكتملة</span>
 </div>
 </div>

 <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
 @foreach($progressMap as $item)
 @php
 $isClickable = in_array($item['status'], ['completed', 'in_progress']);
 $clickableClass = $isClickable ? 'cursor-pointer hover:shadow-md hover:-translate-y-0.5' : 'cursor-default';
 @endphp
 <div class="group relative transition-all duration-300 {{ $clickableClass }}">
 <div class="
 border-2 rounded-xl p-3 transition-all duration-200
 {{ $item['status'] === 'completed' ? 'bg-green-50/80 border-green-400 ' : '' }}
 {{ $item['status'] === 'in_progress' ? 'bg-blue-50/80 border-blue-400 ' : '' }}
 {{ $item['status'] === 'pending' ? 'bg-gray-50/50 border-gray-200 ' : '' }}
 hover:shadow-lg
 ">
 <div class="text-center">
 <div class="
 text-base font-bold mb-1
 {{ $item['status'] === 'completed' ? 'text-green-700 ' : '' }}
 {{ $item['status'] === 'in_progress' ? 'text-blue-700 ' : '' }}
 {{ $item['status'] === 'pending' ? 'text-gray-500 ' : '' }}
 ">
 {{ $item['surah_id'] }}
 </div>
 <div class="text-xs font-medium text-gray-700 mb-2 leading-tight line-clamp-2">
 {{ $item['surah_name'] }}
 </div>

 {{-- Progress Bar --}}
 <x-progress
 :value="$item['progress_percent']"
 :max="100"
 size="sm"
 :variant="$item['status'] === 'completed' ? 'success' : ($item['status'] === 'in_progress' ? 'primary' : 'warning')"
 />
 </div>
 </div>

 {{-- Tooltip on Hover --}}
 <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900/95 backdrop-blur-sm text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap z-10 shadow-lg">
 <div class="font-semibold mb-1">{{ $item['surah_name'] }}</div>
 <div>{{ $item['memorized_ayahs'] }} من {{ $item['total_ayahs'] }} آية</div>
 <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-900/95"></div>
 </div>
 </div>
 @endforeach
 </div>
 </x-card>

 {{-- Info Alert --}}
 <x-alert variant="info" dismissible>
 <div class="font-semibold mb-1">طريقة حساب التقدم</div>
 <p class="text-sm leading-relaxed">يتم حساب التقدم بناءً على الجلسات المسجلة للآيات الجديدة التي تم حضورها فقط. الآيات التي تم تسميعها في جلسات المراجعة لا تظهر في هذه الخريطة.</p>
 </x-alert>
</div>
@endsection
