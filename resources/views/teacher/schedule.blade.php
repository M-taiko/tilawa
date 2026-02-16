@extends('layouts.app')

@section('title', 'جدولي الأسبوعي')

@section('content')
@php
    $orderedDays = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
    $allSchedules = collect($schedules)->flatten(1);
    $totalSchedules = $allSchedules->count();
    $totalMinutes = $allSchedules->sum('duration_minutes');
    $totalHours = $totalMinutes ? round($totalMinutes / 60, 1) : 0;
    $daysWithSchedules = collect($schedules)
        ->filter(fn ($daySchedules) => $daySchedules->count() > 0)
        ->keys()
        ->values();
    $activeDaysCount = $daysWithSchedules->count();
    $busiestDayKey = collect($schedules)->sortByDesc(fn ($daySchedules) => $daySchedules->count())->keys()->first();
    $busiestDayName = $busiestDayKey ? ($dayNames[$busiestDayKey] ?? '-') : '-';
@endphp

{{-- Page Header --}}
<div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-gradient-to-br from-white via-white to-slate-50 p-6 mb-6">
 <div class="absolute -top-12 -right-12 h-40 w-40 rounded-full bg-primary-100/70 blur-2xl"></div>
 <div class="absolute -bottom-16 -left-10 h-48 w-48 rounded-full bg-blue-100/60 blur-3xl"></div>
 <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
 <div>
 <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-50 text-primary-700 text-xs font-semibold border border-primary-100">
 <span class="h-2 w-2 rounded-full bg-primary-500"></span>
 نظرة أسبوعية
 </div>
 <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-3">جدولي الأسبوعي</h1>
 <p class="text-sm text-slate-600 mt-2">عرض سريع ومنظم لمواعيد حلقاتي خلال الأسبوع</p>
 </div>
 <x-button href="{{ route('teacher.dashboard') }}" variant="ghost">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
 </svg>
 رجوع
 </x-button>
 </div>
</div>

{{-- Summary Stats --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
 <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
 <div class="text-xs font-semibold text-slate-500">إجمالي المواعيد</div>
 <div class="mt-2 text-2xl font-bold text-slate-900">{{ $totalSchedules }}</div>
 <div class="mt-1 text-xs text-slate-400">عدد الحصص خلال الأسبوع</div>
 </div>
 <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
 <div class="text-xs font-semibold text-slate-500">إجمالي الساعات</div>
 <div class="mt-2 text-2xl font-bold text-slate-900">{{ $totalHours }}</div>
 <div class="mt-1 text-xs text-slate-400">مجموع مدة الحصص</div>
 </div>
 <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
 <div class="text-xs font-semibold text-slate-500">اليوم الأكثر انشغالاً</div>
 <div class="mt-2 text-2xl font-bold text-slate-900">{{ $busiestDayName }}</div>
 <div class="mt-1 text-xs text-slate-400">أكثر يوم فيه مواعيد</div>
 </div>
 <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
 <div class="text-xs font-semibold text-slate-500">المتوسط اليومي</div>
 <div class="mt-2 text-2xl font-bold text-slate-900">{{ $activeDaysCount ? round($totalSchedules / $activeDaysCount, 1) : 0 }}</div>
 <div class="mt-1 text-xs text-slate-400">مواعيد لكل يوم فعّال</div>
 </div>
</div>

{{-- Weekly Calendar Grid --}}
<x-card>
 <x-card-body>
 @if($daysWithSchedules->isEmpty())
 <div class="flex flex-col items-center justify-center text-center py-12">
 <div class="h-14 w-14 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400 mb-4">
 <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
 </svg>
 </div>
 <p class="text-base font-semibold text-slate-900">لا توجد مواعيد هذا الأسبوع</p>
 <p class="text-sm text-slate-500 mt-1">ستظهر الأيام تلقائياً عند إضافة المواعيد</p>
 </div>
 @else
 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
 @foreach($orderedDays as $day)
 @if($daysWithSchedules->contains($day))
 <div class="group border border-slate-200 rounded-xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-all">
 {{-- Day Header --}}
 <div class="flex items-center justify-between px-4 py-3 bg-gradient-to-r from-primary-600 to-primary-500 text-white">
 <span class="font-bold">{{ $dayNames[$day] }}</span>
 <span class="text-xs font-semibold bg-white/20 px-2 py-0.5 rounded-full">
 {{ $schedules[$day]->count() }}
 </span>
 </div>

 {{-- Day Content --}}
 <div class="p-3 space-y-3 bg-slate-50 min-h-[220px]">
 @forelse($schedules[$day] as $schedule)
 @php
     $startTime = \Carbon\Carbon::parse($schedule->start_time)->format('h:i A');
     $endTime = \Carbon\Carbon::parse($schedule->end_time)->format('h:i A');
 @endphp
 <div class="bg-white rounded-xl p-3 border border-slate-200 shadow-sm hover:shadow-md transition-shadow duration-200">
 <div class="flex items-start justify-between gap-2">
 <div class="font-bold text-sm text-slate-900">{{ $schedule->studyClass->name }}</div>
 <span class="text-[10px] font-semibold text-primary-700 bg-primary-50 border border-primary-100 rounded-full px-2 py-0.5">
 {{ $schedule->duration_minutes }} دقيقة
 </span>
 </div>
 <div class="mt-2 flex items-center gap-2 text-xs text-slate-600">
 <span class="inline-flex items-center gap-1">
 <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 {{ $startTime }} - {{ $endTime }}
 </span>
 </div>
 @if($schedule->location)
 <div class="mt-2 inline-flex items-center gap-1 text-xs text-slate-600">
 <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
 </svg>
 {{ $schedule->location }}
 </div>
 @endif
 </div>
 @empty
 <div class="flex flex-col items-center justify-center text-center text-slate-400 text-sm py-10 gap-2">
 <div class="h-10 w-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
 </svg>
 </div>
 لا توجد مواعيد
 </div>
 @endforelse
 </div>
 </div>
 @endif
 @endforeach
 </div>
 @endif
 </x-card-body>
</x-card>
@endsection
