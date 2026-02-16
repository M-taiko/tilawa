@extends('layouts.app')

@section('title', 'أعباء العمل للمعلمين')

@section('content')
<div class="max-w-7xl mx-auto">
 {{-- Page Header --}}
 <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
 <div>
 <h1 class="text-3xl font-bold text-gray-900">أعباء العمل للمعلمين</h1>
 <p class="text-sm text-gray-500 mt-1">تحليل أحمال العمل والتوزيع الأمثل للمعلمين</p>
 </div>
 <div class="flex items-center gap-3">
 <x-button variant="secondary" href="{{ route('admin.teachers.index') }}">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
 </svg>
 رجوع للمعلمين
 </x-button>
 </div>
 </div>

 {{-- Summary Cards --}}
 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
 <x-compact-stat-card
 label="إجمالي المعلمين"
 :value="$summary['total_teachers']">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
 </svg>
 </x-compact-stat-card>

 <x-compact-stat-card
 label="محمّل بشكل زائد"
 :value="$summary['overloaded']">
 <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
 </svg>
 </x-compact-stat-card>

 <x-compact-stat-card
 label="عبء عالي"
 :value="$summary['high_workload']">
 <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
 </svg>
 </x-compact-stat-card>

 <x-compact-stat-card
 label="عبء عادي"
 :value="$summary['normal_workload']">
 <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </x-compact-stat-card>

 <x-compact-stat-card
 label="عبء منخفض"
 :value="$summary['low_workload']">
 <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
 </svg>
 </x-compact-stat-card>
 </div>

 {{-- Legend --}}
 <x-card class="mb-6">
 <div class="p-5">
 <div class="flex items-start gap-4 mb-4">
 <svg class="w-5 h-5 text-gray-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 <div class="flex-1">
 <h3 class="text-sm font-semibold text-gray-900 mb-3">مستويات عبء العمل</h3>
 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
 <div class="flex items-start gap-2">
 <div class="w-4 h-4 rounded-full bg-red-500 mt-0.5 flex-shrink-0"></div>
 <div>
 <p class="text-sm font-medium text-gray-900">محمّل بشكل زائد</p>
 <p class="text-xs text-gray-500">+30 طالب أو +25 جلسة/أسبوع</p>
 </div>
 </div>
 <div class="flex items-start gap-2">
 <div class="w-4 h-4 rounded-full bg-orange-500 mt-0.5 flex-shrink-0"></div>
 <div>
 <p class="text-sm font-medium text-gray-900">عبء عالي</p>
 <p class="text-xs text-gray-500">20-30 طالب أو 15-25 جلسة/أسبوع</p>
 </div>
 </div>
 <div class="flex items-start gap-2">
 <div class="w-4 h-4 rounded-full bg-green-500 mt-0.5 flex-shrink-0"></div>
 <div>
 <p class="text-sm font-medium text-gray-900">عبء عادي</p>
 <p class="text-xs text-gray-500">10-20 طالب أو 8-15 جلسة/أسبوع</p>
 </div>
 </div>
 <div class="flex items-start gap-2">
 <div class="w-4 h-4 rounded-full bg-gray-400 mt-0.5 flex-shrink-0"></div>
 <div>
 <p class="text-sm font-medium text-gray-900">عبء منخفض</p>
 <p class="text-xs text-gray-500">-10 طالب أو -8 جلسة/أسبوع</p>
 </div>
 </div>
 </div>
 </div>
 </div>
 </div>
 </x-card>

 {{-- Workload List --}}
 <div class="space-y-5">
 @forelse($workloads as $item)
 @php
 $workload = $item['workload'];
 $levelConfig = [
 'overloaded' => [
 'variant' => 'red',
 'badge' => 'error',
 'label' => 'محمّل بشكل زائد',
 'bg' => 'bg-red-50/50'
 ],
 'high' => [
 'variant' => 'amber',
 'badge' => 'warning',
 'label' => 'عبء عالي',
 'bg' => 'bg-orange-50/50'
 ],
 'normal' => [
 'variant' => 'green',
 'badge' => 'success',
 'label' => 'عبء عادي',
 'bg' => 'bg-green-50/50'
 ],
 'low' => [
 'variant' => null,
 'badge' => 'neutral',
 'label' => 'عبء منخفض',
 'bg' => 'bg-gray-50/50'
 ],
 ];
 $config = $levelConfig[$workload['workload_level']] ?? $levelConfig['normal'];
 @endphp

 <x-card :variant="$config['variant']" class="{{ $config['bg'] }}">
 <div class="p-6">
 {{-- Header --}}
 <div class="flex items-start justify-between gap-4 mb-5">
 <div class="flex-1">
 <div class="flex items-center gap-3 mb-2">
 <h3 class="text-xl font-bold text-gray-900">{{ $item['teacher_name'] }}</h3>
 <x-badge :variant="$config['badge']">
 {{ $config['label'] }}
 </x-badge>
 </div>
 <p class="text-sm text-gray-500">معلم نشط - آخر تحديث: {{ now()->format('Y-m-d') }}</p>
 </div>
 <a href="{{ route('admin.reports.teacher', $item['teacher_id']) }}"
 class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
 </svg>
 التقرير التفصيلي
 </a>
 </div>

 {{-- Metrics Grid --}}
 <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-4">
 <x-workload-metric
 label="الطلاب النشطين"
 :value="$workload['active_students']"
 iconColor="blue">
 <x-slot:icon>
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
 </svg>
 </x-slot:icon>
 </x-workload-metric>

 <x-workload-metric
 label="الحلقات النشطة"
 :value="$workload['active_classes']"
 iconColor="purple">
 <x-slot:icon>
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
 </svg>
 </x-slot:icon>
 </x-workload-metric>

 <x-workload-metric
 label="جلسات هذا الأسبوع"
 :value="$workload['sessions_this_week']"
 iconColor="green">
 <x-slot:icon>
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
 </svg>
 </x-slot:icon>
 </x-workload-metric>

 <x-workload-metric
 label="معدل جلسات/يوم"
 :value="$workload['avg_sessions_per_day']"
 iconColor="orange">
 <x-slot:icon>
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </x-slot:icon>
 </x-workload-metric>

 <x-workload-metric
 label="نسبة الحضور"
 :value="$workload['attendance_rate'] . '%'"
 :progress="$workload['attendance_rate']"
 progressVariant="info"
 iconColor="cyan">
 <x-slot:icon>
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </x-slot:icon>
 </x-workload-metric>

 <x-workload-metric
 label="معدل الدرجات"
 :value="$workload['avg_score']"
 iconColor="yellow">
 <x-slot:icon>
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
 </svg>
 </x-slot:icon>
 </x-workload-metric>
 </div>

 {{-- Overloaded Warning --}}
 @if($workload['workload_level'] === 'overloaded')
 <x-alert variant="error" class="mt-4">
 <div>
 <p class="font-semibold text-sm mb-1">تحذير: عبء عمل زائد</p>
 <p class="text-sm">يُنصح بإعادة توزيع بعض الطلاب أو الحلقات على معلمين آخرين لتحسين جودة التعليم والحفاظ على رفاهية المعلم.</p>
 </div>
 </x-alert>
 @elseif($workload['workload_level'] === 'low')
 <x-alert variant="info" class="mt-4">
 <div>
 <p class="font-semibold text-sm mb-1">عبء عمل منخفض</p>
 <p class="text-sm">يمكن إسناد المزيد من الطلاب أو الحلقات لهذا المعلم لتحسين الاستفادة من الموارد.</p>
 </div>
 </x-alert>
 @endif
 </div>
 </x-card>
 @empty
 <x-card class="text-center py-16">
 <svg class="w-20 h-20 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
 </svg>
 <h3 class="text-xl font-semibold text-gray-900 mb-2">لا يوجد معلمون نشطون</h3>
 <p class="text-gray-500 mb-6">قم بإضافة معلمين لعرض أعباء العمل</p>
 <x-button variant="primary" href="{{ route('admin.teachers.create') }}">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
 </svg>
 إضافة معلم جديد
 </x-button>
 </x-card>
 @endforelse
 </div>
</div>
@endsection
