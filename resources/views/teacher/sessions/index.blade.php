@extends('layouts.app')

@section('title', 'الجلسات')

@section('content')
@php
    $totalSessions = method_exists($sessions, 'total') ? $sessions->total() : $sessions->count();
    $pageSessions = $sessions->count();
    $attendanceCounts = collect($sessions->items())->groupBy('attendance_status')->map->count();
    $presentCount = $attendanceCounts['present'] ?? 0;
    $absentCount = $attendanceCounts['absent'] ?? 0;
    $excusedCount = $attendanceCounts['excused'] ?? 0;
    $sessionTypes = collect($sessions->items())->groupBy('session_type')->map->count();
    $newMemorization = $sessionTypes['new'] ?? 0;
    $revision = $sessionTypes['revision'] ?? 0;
    $foundation = $sessionTypes['foundation'] ?? 0;
@endphp

{{-- Page Header --}}
<div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-gradient-to-br from-white via-white to-slate-50 p-6 mb-6">
 <div class="absolute -top-12 -right-12 h-40 w-40 rounded-full bg-primary-100/70 blur-2xl"></div>
 <div class="absolute -bottom-16 -left-10 h-48 w-48 rounded-full bg-blue-100/60 blur-3xl"></div>
 <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
 <div>
 <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-50 text-primary-700 text-xs font-semibold border border-primary-100">
 <span class="h-2 w-2 rounded-full bg-primary-500"></span>
 سجل الجلسات
 </div>
 <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-3">الجلسات</h1>
 <p class="text-sm text-slate-600 mt-2">نظرة سريعة على جلسات الحفظ والمراجعة</p>
 </div>
 <div class="flex items-center gap-2">
 <x-button href="{{ route('teacher.export.sessions', request()->query()) }}" variant="outline">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
 </svg>
 تصدير
 </x-button>
 <x-button href="{{ route('teacher.sessions.create') }}" variant="primary" class="shadow-lg shadow-primary-500/20">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
 </svg>
 جلسة جديدة
 </x-button>
 </div>
 </div>
</div>

 {{-- Quick Stats --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
 <x-gradient-stat-card label="إجمالي الجلسات" :value="$totalSessions" gradient="blue">
 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </x-gradient-stat-card>

 <x-gradient-stat-card label="حاضر" :value="$presentCount" gradient="green">
 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </x-gradient-stat-card>

 <x-gradient-stat-card label="غائب / معتذر" :value="$absentCount + $excusedCount" gradient="red">
 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </x-gradient-stat-card>

 <x-gradient-stat-card label="نسبة الحضور" :value="$totalSessions > 0 ? round(($presentCount / $totalSessions) * 100) . '%' : '0%'" gradient="purple">
 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
 </svg>
 </x-gradient-stat-card>
</div>

 {{-- Secondary Stats --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
 <x-compact-stat-card label="حفظ جديد" :value="$newMemorization">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
 </svg>
 </x-compact-stat-card>

 <x-compact-stat-card label="مراجعة" :value="$revision">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
 </svg>
 </x-compact-stat-card>

 <x-compact-stat-card label="تأسيس" :value="$foundation">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
 </svg>
 </x-compact-stat-card>

 <x-compact-stat-card label="جلسات هذه الصفحة" :value="$pageSessions">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
 </svg>
 </x-compact-stat-card>
</div>

 {{-- Sessions Table --}}
<x-card>
 <x-card-body>
 <div class="flex items-center justify-between mb-4">
 <div>
 <h3 class="text-lg font-bold text-slate-900">سجل الجلسات</h3>
 <p class="text-sm text-slate-500 mt-1">تفاصيل الجلسات وتقييم الأداء</p>
 </div>
 </div>

 <x-table>
 <x-table.head>
 <x-table.heading>التاريخ</x-table.heading>
 <x-table.heading>الطالب</x-table.heading>
 <x-table.heading>النوع</x-table.heading>
 <x-table.heading>الحضور</x-table.heading>
 <x-table.heading>المحتوى</x-table.heading>
 <x-table.heading>الكمية</x-table.heading>
 <x-table.heading>التقييم</x-table.heading>
 <x-table.heading>الإجراءات</x-table.heading>
 </x-table.head>
 <x-table.body>
 @forelse ($sessions as $session)
 <x-table.row>
 <x-table.cell>
 {{ $session->date->format('Y-m-d') }}
 </x-table.cell>
 <x-table.cell class="font-medium text-gray-900">
 {{ $session->student?->name }}
 </x-table.cell>
 <x-table.cell>
 @if($session->session_type === 'new')
 <x-badge variant="success">حفظ جديد</x-badge>
 @elseif($session->session_type === 'revision')
 <x-badge variant="primary">مراجعة</x-badge>
 @else
 <x-badge variant="info">تأسيس</x-badge>
 @endif
 </x-table.cell>
 <x-table.cell>
 @if($session->attendance_status === 'present')
 <x-badge variant="success">حاضر</x-badge>
 @elseif($session->attendance_status === 'absent')
 <x-badge variant="danger">غائب</x-badge>
 @else
 <x-badge variant="warning">معتذر</x-badge>
 @endif
 </x-table.cell>
 <x-table.cell>
 @if ($session->session_type === 'foundation')
  @php
      $skillNames = $session->foundationSkills
          ->map(fn ($skill) => $skill->name ?? $skill->name_ar)
          ->filter()
          ->values();
      $skillsDisplay = $skillNames->take(2)->implode('، ');
      $moreCount = $skillNames->count() - 2;
  @endphp
  @if($skillNames->isNotEmpty())
   <span class="text-sm text-slate-700">{{ $skillsDisplay }}</span>
   @if($moreCount > 0)
    <span class="text-xs text-slate-500">+{{ $moreCount }}</span>
   @endif
  @else
   -
  @endif
  @elseif ($session->surah)
  {{ $session->surah->name_arabic }} ({{ $session->ayah_from }}-{{ $session->ayah_to }})
  @else
  -
 @endif
 </x-table.cell>
 <x-table.cell>
 @if ($session->session_type === 'foundation')
 @php
     $masteryAvg = $session->foundationSkills->isNotEmpty()
         ? round($session->foundationSkills->avg(fn ($skill) => (int) $skill->pivot->mastery_percent))
         : ($session->mastery_progress ?? 0);
 @endphp
 {{ $masteryAvg }}%
 @else
 {{ $session->ayah_count }} آية
 @endif
 </x-table.cell>
 <x-table.cell>
 <div class="flex items-center gap-2">
 <span class="text-sm font-bold text-gray-900">{{ $session->score }}</span>
 <div class="w-16 bg-gray-200 rounded-full h-1.5">
 <div class="h-1.5 rounded-full {{ $session->score >= 8 ? 'bg-green-500' : ($session->score >= 5 ? 'bg-amber-500' : 'bg-red-500') }}" style="width: {{ ($session->score / 10) * 100 }}%"></div>
 </div>
 </div>
 </x-table.cell>
 <x-table.cell>
 <div class="flex items-center justify-end">
 <x-button href="{{ route('teacher.sessions.edit', $session) }}" variant="outline" size="sm">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
 </svg>
 تعديل
 </x-button>
 </div>
 </x-table.cell>
 </x-table.row>
 @empty
 <x-table.empty title="لا توجد جلسات" description="ابدأ بإضافة جلسة جديدة" cols="8">
 <x-slot:icon>
 <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </x-slot:icon>
 </x-table.empty>
 @endforelse
 </x-table.body>
 </x-table>
 </x-card-body>
</x-card>

<div class="mt-6">
 <x-pagination :paginator="$sessions" align="center" />
</div>
@endsection
