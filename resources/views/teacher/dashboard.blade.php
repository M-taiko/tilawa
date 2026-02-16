@extends('layouts.app')

@section('title', 'لوحة المعلم')

@section('content')
{{-- Welcome Header --}}
<div class="mb-8">
 <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
 <div>
 <h1 class="text-3xl font-bold text-gray-900 mb-2">مرحباً بك، {{ auth()->user()->name }} 👋</h1>
 <p class="text-gray-600 flex items-center gap-2">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
 </svg>
 {{ now()->locale('ar')->translatedFormat('l, j F Y') }}
 </p>
 </div>

 {{-- Quick Actions Dropdown --}}
 <div class="flex items-center gap-3">
 <div class="relative group">
 <button type="button" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 shadow-sm">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
 </svg>
 إجراءات سريعة
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
 </svg>
 </button>
 <div class="absolute left-0 mt-2 w-56 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
 <div class="py-2">
 <a href="{{ route('teacher.sessions.create') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 hover:bg-gray-50 transition-colors">
 <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 <span class="font-medium">تسجيل جلسة</span>
 </a>
 <a href="{{ route('teacher.students.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 hover:bg-gray-50 transition-colors">
 <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
 </svg>
 <span class="font-medium">عرض الطلاب</span>
 </a>
 <a href="{{ route('teacher.reports.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 hover:bg-gray-50 transition-colors">
 <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
 </svg>
 <span class="font-medium">التقارير</span>
 </a>
 </div>
 </div>
 </div>

 <x-button href="{{ route('teacher.sessions.create') }}" variant="primary" class="shadow-lg shadow-primary-500/20">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
 </svg>
 تسجيل جلسة جديدة
 </x-button>
 </div>
 </div>
</div>

 {{-- Key Stats Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
 {{-- Active Students --}}
 <div class="relative overflow-hidden bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
 <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-white/10"></div>
 <div class="relative">
 <div class="flex items-center justify-between mb-4">
 <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm">
 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
 </svg>
 </div>
 </div>
 <div>
 <p class="text-blue-100 text-sm font-medium mb-1">الطلاب النشطون</p>
 <p class="text-3xl font-bold">{{ $activeStudentsCount }}</p>
 <p class="text-blue-100 text-sm mt-2">{{ round(($activeStudentsCount / max($studentsCount, 1)) * 100, 1) }}% من الإجمالي</p>
 </div>
 </div>
 </div>

 {{-- Attendance Rate --}}
 <div class="relative overflow-hidden bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
 <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-white/10"></div>
 <div class="relative">
 <div class="flex items-center justify-between mb-4">
 <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm">
 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </div>
 </div>
 <div>
 <p class="text-green-100 text-sm font-medium mb-1">نسبة الحضور</p>
 <p class="text-3xl font-bold">{{ $attendanceRate }}%</p>
 <p class="text-green-100 text-sm mt-2">آخر 30 يوم</p>
 </div>
 </div>
 </div>

 {{-- Ayahs This Month --}}
 <div class="relative overflow-hidden bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
 <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-white/10"></div>
 <div class="relative">
 <div class="flex items-center justify-between mb-4">
 <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm">
 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
 </svg>
 </div>
 </div>
 <div>
 <p class="text-purple-100 text-sm font-medium mb-1">آيات هذا الشهر</p>
 <p class="text-3xl font-bold">{{ number_format($totalAyahsThisMonth) }}</p>
 <p class="text-purple-100 text-sm mt-2">حفظ جديد</p>
 </div>
 </div>
 </div>

 {{-- Sessions Today --}}
 <div class="relative overflow-hidden bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl shadow-lg p-6 text-white">
 <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-white/10"></div>
 <div class="relative">
 <div class="flex items-center justify-between mb-4">
 <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm">
 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </div>
 </div>
 <div>
 <p class="text-amber-100 text-sm font-medium mb-1">جلسات اليوم</p>
 <p class="text-3xl font-bold">{{ $sessionsToday }}</p>
 <p class="text-amber-100 text-sm mt-2">{{ now()->format('Y-m-d') }}</p>
 </div>
 </div>
 </div>
</div>

 {{-- Quick Stats Row --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
 <div class="bg-white rounded-lg border border-gray-200 p-4">
 <div class="flex items-center gap-3">
 <div class="p-2 bg-gray-100 rounded-lg">
 <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
 </svg>
 </div>
 <div>
 <p class="text-2xl font-bold text-gray-900">{{ $studentsCount }}</p>
 <p class="text-xs text-gray-500">إجمالي الطلاب</p>
 </div>
 </div>
 </div>

 <div class="bg-white rounded-lg border border-gray-200 p-4">
 <div class="flex items-center gap-3">
 <div class="p-2 bg-gray-100 rounded-lg">
 <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
 </svg>
 </div>
 <div>
 <p class="text-2xl font-bold text-gray-900">{{ $classPerformance->count() }}</p>
 <p class="text-xs text-gray-500">الحلقات</p>
 </div>
 </div>
 </div>

 <div class="bg-white rounded-lg border border-gray-200 p-4">
 <div class="flex items-center gap-3">
 <div class="p-2 bg-gray-100 rounded-lg">
 <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
 </svg>
 </div>
 <div>
 <p class="text-2xl font-bold text-gray-900">{{ number_format($totalNewAyahs) }}</p>
 <p class="text-xs text-gray-500">إجمالي الآيات</p>
 </div>
 </div>
 </div>

 <div class="bg-white rounded-lg border border-gray-200 p-4">
 <div class="flex items-center gap-3">
 <div class="p-2 bg-gray-100 rounded-lg">
 <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
 </svg>
 </div>
 <div>
 <p class="text-2xl font-bold text-gray-900">{{ number_format($avgScore ?? 0, 1) }}</p>
 <p class="text-xs text-gray-500">متوسط التقييم</p>
 </div>
 </div>
 </div>
</div>

 {{-- Main Content Grid --}}
<div class="space-y-6 mb-8">
 {{-- Monthly Activity --}}
 <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
 <div class="p-6 border-b border-gray-200">
 <div class="flex items-center justify-between">
 <div>
 <h3 class="text-lg font-bold text-gray-900">النشاط الشهري</h3>
 <p class="text-sm text-gray-500 mt-1">آيات الحفظ والمراجعة - آخر 6 أشهر</p>
 </div>
 <x-button href="{{ route('teacher.reports.index') }}" variant="ghost" size="sm">
 عرض التقارير
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
 </svg>
 </x-button>
 </div>
 </div>
 <div class="p-6">
 <div class="h-[320px]">
 <canvas id="teacherAyahsChart"></canvas>
 </div>
 </div>
 </div>

 {{-- Score & Attendance - Two Column Layout --}}
 <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
 <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
 <div class="p-6 border-b border-gray-200">
 <h3 class="text-lg font-bold text-gray-900">متوسط التقييم</h3>
 <p class="text-sm text-gray-500 mt-1">آخر 6 أشهر</p>
 </div>
 <div class="p-6">
 <div class="h-[320px]">
 <canvas id="teacherScoreChart"></canvas>
 </div>
 </div>
 </div>

 <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
 <div class="p-6 border-b border-gray-200">
 <h3 class="text-lg font-bold text-gray-900">الحضور</h3>
 <p class="text-sm text-gray-500 mt-1">آخر 6 أشهر</p>
 </div>
 <div class="p-6">
 <div class="h-[320px]">
 <canvas id="teacherAttendanceChart"></canvas>
 </div>
 </div>
 </div>
 </div>

 {{-- At-Risk Students --}}
 <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
 <div class="p-6 border-b border-gray-200">
 <div>
 <h3 class="text-lg font-bold text-gray-900">طلاب يحتاجون متابعة</h3>
 <p class="text-sm text-gray-500 mt-1">لم يحضروا منذ 14+ يوم</p>
 </div>
 </div>
 <div class="p-6">
 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
 @forelse($atRiskStudents->take(10) as $student)
 <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-red-300 hover:shadow-sm transition-all">
 <div class="flex items-start justify-between mb-2">
 <div class="p-2 bg-red-100 rounded-lg">
 <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
 </svg>
 </div>
 </div>
 <div>
 <p class="font-semibold text-gray-900 mb-1">{{ $student->name }}</p>
 <p class="text-xs text-gray-500 mb-2">{{ $student->class?->name ?? 'غير معيّن' }}</p>
 @if($student->last_session_date)
 <p class="text-xs text-red-600 font-medium">{{ \Carbon\Carbon::parse($student->last_session_date)->diffForHumans() }}</p>
 @else
 <p class="text-xs text-red-600 font-medium">لم يحضر مطلقاً</p>
 @endif
 </div>
 </div>
 @empty
 <div class="col-span-full text-center py-8">
 <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-4">
 <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
 </svg>
 </div>
 <p class="text-base text-gray-900 font-semibold mb-1">جميع الطلاب نشطون</p>
 <p class="text-sm text-gray-500">لا يوجد طلاب يحتاجون متابعة</p>
 </div>
 @endforelse
 </div>
 </div>
 </div>
</div>

 {{-- Top Students & Class Performance --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
 {{-- Top Students --}}
 <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
 <div class="p-6 border-b border-gray-200">
 <div class="flex items-center justify-between">
 <div>
 <h3 class="text-lg font-bold text-gray-900">أفضل 10 طلاب</h3>
 <p class="text-sm text-gray-500 mt-1">آخر 30 يوم</p>
 </div>
 <x-button href="{{ route('teacher.students.index') }}" variant="ghost" size="sm">
 عرض الكل
 </x-button>
 </div>
 </div>
 <div class="p-6">
 @forelse($topStudents as $student)
 <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
 <div class="flex items-center gap-3">
 <div class="flex-shrink-0 w-8 h-8 rounded-full {{ $loop->index === 0 ? 'bg-gradient-to-br from-amber-400 to-amber-600' : ($loop->index === 1 ? 'bg-gradient-to-br from-gray-300 to-gray-500' : ($loop->index === 2 ? 'bg-gradient-to-br from-amber-700 to-amber-800' : 'bg-gray-200')) }} flex items-center justify-center text-white font-bold text-sm shadow-sm">
 {{ $loop->iteration }}
 </div>
 <div>
 <p class="font-semibold text-gray-900">{{ $student->name }}</p>
 <p class="text-xs text-gray-500">{{ $student->class?->name ?? 'غير معيّن' }}</p>
 </div>
 </div>
 <div class="text-left">
 <p class="text-lg font-bold text-green-600">{{ $student->total_memorized }}</p>
 <p class="text-xs text-gray-500">آية</p>
 </div>
 </div>
 @empty
 <p class="text-center text-gray-500 py-8">لا توجد بيانات</p>
 @endforelse
 </div>
 </div>

 {{-- Class Performance --}}
 <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
 <div class="p-6 border-b border-gray-200">
 <div>
 <h3 class="text-lg font-bold text-gray-900">أداء الحلقات</h3>
 <p class="text-sm text-gray-500 mt-1">آخر 30 يوم</p>
 </div>
 </div>
 <div class="p-6">
 @forelse($classPerformance->take(6) as $class)
 @php
 $performanceScore = 0;
 if ($class->students_count > 0) {
 $performanceScore = ($class->total_ayahs / $class->students_count) + ($class->avg_score * 2);
 }
 $performanceLevel = $performanceScore >= 30 ? 'excellent' : ($performanceScore >= 15 ? 'good' : 'needs-attention');
 @endphp
 <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
 <div class="flex-1">
 <p class="font-semibold text-gray-900">{{ $class->name }}</p>
 <div class="flex items-center gap-3 mt-1 text-xs text-gray-500">
 <span>{{ $class->students_count }} طالب</span>
 <span>•</span>
 <span>{{ $class->sessions_count }} جلسة</span>
 </div>
 </div>
 <div class="flex items-center gap-3">
 <div class="text-left">
 <p class="text-lg font-bold text-purple-600">{{ $class->total_ayahs }}</p>
 <p class="text-xs text-gray-500">آية</p>
 </div>
 @if($performanceLevel === 'excellent')
 <div class="w-2 h-2 rounded-full bg-green-500"></div>
 @elseif($performanceLevel === 'good')
 <div class="w-2 h-2 rounded-full bg-blue-500"></div>
 @else
 <div class="w-2 h-2 rounded-full bg-amber-500"></div>
 @endif
 </div>
 </div>
 @empty
 <p class="text-center text-gray-500 py-8">لا توجد بيانات</p>
 @endforelse
 </div>
 </div>
</div>

 {{-- Chart.js Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1"></script>
<script>
Chart.defaults.color = '#64748b';
Chart.defaults.font.family = "'Tajawal', sans-serif";

// Activity Chart
new Chart(document.getElementById('teacherAyahsChart'), {
 type: 'bar',
 data: {
 labels: @json($monthLabels),
 datasets: [
 {
 label: 'آيات حفظ جديد',
 data: @json($ayahsData),
 backgroundColor: '#10b981',
 borderRadius: 6,
 },
 {
 label: 'آيات مراجعة',
 data: @json($reviewData),
 backgroundColor: '#3b82f6',
 borderRadius: 6,
 }
 ]
 },
 options: {
 responsive: true,
 maintainAspectRatio: false,
 plugins: {
 legend: { position: 'bottom', labels: { padding: 15, usePointStyle: true } }
 },
 scales: {
 y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
 x: { grid: { display: false } }
 }
 }
});

// Score Chart
new Chart(document.getElementById('teacherScoreChart'), {
 type: 'line',
 data: {
 labels: @json($monthLabels),
 datasets: [{
 label: 'متوسط التقييم',
 data: @json($avgScoreData),
 borderColor: '#0ea5e9',
 backgroundColor: 'rgba(14,165,233,0.1)',
 fill: true,
 tension: 0.4,
 borderWidth: 2,
 pointRadius: 3,
 }]
 },
 options: {
 responsive: true,
 maintainAspectRatio: false,
 plugins: { legend: { display: false } },
 scales: {
 y: { beginAtZero: true, max: 10, grid: { color: '#f1f5f9' } },
 x: { grid: { display: false } }
 }
 }
});

// Attendance Chart
new Chart(document.getElementById('teacherAttendanceChart'), {
 type: 'bar',
 data: {
 labels: @json($monthLabels),
 datasets: [
 { label: 'حاضر', data: @json($presentData), backgroundColor: '#10b981', borderRadius: 4 },
 { label: 'غائب', data: @json($absentData), backgroundColor: '#ef4444', borderRadius: 4 },
 { label: 'معتذر', data: @json($excusedData), backgroundColor: '#f59e0b', borderRadius: 4 }
 ]
 },
 options: {
 responsive: true,
 maintainAspectRatio: false,
 plugins: { legend: { position: 'bottom', labels: { padding: 15, usePointStyle: true } } },
 scales: {
 x: { stacked: true, grid: { display: false } },
 y: { stacked: true, beginAtZero: true, grid: { color: '#f1f5f9' } }
 }
 }
});
</script>
@endsection
