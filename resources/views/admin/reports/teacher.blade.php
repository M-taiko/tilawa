@extends('layouts.app')

@section('title', 'تقرير المعلم - ' . $teacher->name)

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-3xl font-bold text-gray-900">تقرير المعلم</h1>
                <x-badge variant="primary">{{ $teacher->name }}</x-badge>
            </div>
            <p class="text-sm text-gray-500">تحليل شامل للأداء والإحصائيات التفصيلية</p>
        </div>
        <div class="flex items-center gap-3">
            <x-button href="{{ route('admin.teachers.workload') }}" variant="secondary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                عرض الأعباء
            </x-button>
            <x-button href="{{ route('admin.reports.index') }}" variant="ghost">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                رجوع
            </x-button>
        </div>
    </div>

    {{-- Date Filter --}}
    <x-card class="mb-8">
        <x-card-header>
            <div class="flex items-center gap-3">
                <div class="p-2 rounded-lg bg-primary-100 text-primary-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900">الفترة الزمنية للتقرير</h3>
            </div>
        </x-card-header>
        <x-card-body>
            <form method="GET" action="{{ route('admin.reports.teacher', $teacher->id) }}" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">من تاريخ</label>
                    <input type="date" name="start_date" class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg text-gray-900 text-sm transition-colors focus:outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-500/20" value="{{ $startDate }}">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">إلى تاريخ</label>
                    <input type="date" name="end_date" class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg text-gray-900 text-sm transition-colors focus:outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-500/20" value="{{ $endDate }}">
                </div>
                <div class="flex items-end">
                    <x-button type="submit" variant="primary" class="w-full">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        تحديث التقرير
                    </x-button>
                </div>
            </form>
        </x-card-body>
    </x-card>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-stat-card
            label="عدد الجلسات"
            :value="$report?->sessions_count ?? 0"
            color="primary">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </x-stat-card>

        <x-stat-card
            label="متوسط التقييم"
            :value="number_format($report?->avg_score ?? 0, 2)"
            color="success">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
            </svg>
        </x-stat-card>

        <x-stat-card
            label="إجمالي آيات جديدة"
            :value="$report?->total_new_ayahs ?? 0"
            color="warning">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </x-stat-card>

        <x-stat-card
            label="طلاب نشطون"
            :value="$report?->active_students ?? 0"
            color="info">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        </x-stat-card>
    </div>

    {{-- Attendance Breakdown --}}
    @if($attendanceBreakdown && $attendanceBreakdown->total_sessions > 0)
    <x-card class="mb-8">
        <x-card-header>
            <div class="flex items-center gap-3">
                <div class="p-2 rounded-lg bg-cyan-100 text-cyan-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900">تفصيل الحضور</h3>
            </div>
        </x-card-header>
        <x-card-body>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-gray-900 mb-1">{{ $attendanceBreakdown->total_sessions }}</div>
                    <div class="text-sm text-gray-500">إجمالي الجلسات</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600 mb-1">{{ $attendanceBreakdown->present_count }}</div>
                    <div class="text-sm text-gray-500 mb-2">حضور</div>
                    <x-progress
                        :value="($attendanceBreakdown->present_count / $attendanceBreakdown->total_sessions) * 100"
                        variant="success"
                        size="sm" />
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-red-600 mb-1">{{ $attendanceBreakdown->absent_count }}</div>
                    <div class="text-sm text-gray-500 mb-2">غياب</div>
                    <x-progress
                        :value="($attendanceBreakdown->absent_count / $attendanceBreakdown->total_sessions) * 100"
                        variant="error"
                        size="sm" />
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-yellow-600 mb-1">{{ $attendanceBreakdown->excused_count }}</div>
                    <div class="text-sm text-gray-500 mb-2">غياب بعذر</div>
                    <x-progress
                        :value="($attendanceBreakdown->excused_count / $attendanceBreakdown->total_sessions) * 100"
                        variant="warning"
                        size="sm" />
                </div>
            </div>
        </x-card-body>
    </x-card>
    @endif

    {{-- Two Column Layout for Top Students and Student Scores --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        {{-- Top Students --}}
        <x-card>
            <x-card-header>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg bg-yellow-100 text-yellow-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">أفضل الطلاب</h3>
                    </div>
                    <x-badge variant="success">Top 5</x-badge>
                </div>
            </x-card-header>
            <x-card-body>
                @forelse($topStudents as $index => $student)
                    <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $index === 0 ? 'bg-yellow-100 text-yellow-700' : ($index === 1 ? 'bg-gray-100 text-gray-700' : ($index === 2 ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700')) }} font-bold text-sm">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $student->name }}</p>
                                <p class="text-xs text-gray-500">{{ $student->class?->name ?? 'بدون حلقة' }}</p>
                            </div>
                        </div>
                        <div class="text-left">
                            <p class="text-lg font-bold text-green-600">{{ $student->total_memorized }}</p>
                            <p class="text-xs text-gray-500">آية</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p>لا توجد بيانات متاحة</p>
                    </div>
                @endforelse
            </x-card-body>
        </x-card>

        {{-- Student Scores --}}
        <x-card>
            <x-card-header>
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-purple-100 text-purple-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">متوسط الدرجات حسب الطالب</h3>
                </div>
            </x-card-header>
            <x-card-body>
                <div class="space-y-3">
                    @forelse($studentScores->take(10) as $student)
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm font-medium text-gray-700">{{ $student->name }}</span>
                                <span class="text-sm font-bold text-gray-900">{{ number_format($student->avg_score, 1) }}</span>
                            </div>
                            <x-progress
                                :value="$student->avg_score"
                                :max="10"
                                variant="{{ $student->avg_score >= 8 ? 'success' : ($student->avg_score >= 6 ? 'warning' : 'error') }}"
                                size="sm" />
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <p>لا توجد بيانات متاحة</p>
                        </div>
                    @endforelse
                </div>
            </x-card-body>
        </x-card>
    </div>

    {{-- Inactive Students --}}
    @if($inactiveStudents->isNotEmpty())
    <x-card class="mb-8">
        <x-card-header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-red-100 text-red-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">طلاب غير نشطين</h3>
                </div>
                <x-badge variant="error">{{ $inactiveStudents->count() }}</x-badge>
            </div>
        </x-card-header>
        <x-card-body>
            <x-alert variant="warning" class="mb-4">
                الطلاب التالية أسماؤهم لم يحضروا أي جلسة منذ 14 يوم أو أكثر
            </x-alert>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($inactiveStudents->take(9) as $student)
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex-1">
                                <p class="font-medium text-gray-900">{{ $student->name }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $student->class?->name ?? 'بدون حلقة' }}</p>
                            </div>
                            <x-badge variant="error">غير نشط</x-badge>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-gray-600 mt-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>آخر جلسة: {{ $student->last_session_date ? \Carbon\Carbon::parse($student->last_session_date)->format('Y-m-d') : 'لا توجد جلسات' }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
            @if($inactiveStudents->count() > 9)
                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-600">+ {{ $inactiveStudents->count() - 9 }} طالب آخر غير نشط</p>
                </div>
            @endif
        </x-card-body>
    </x-card>
    @endif

    {{-- Session Trends (Last 6 Months) --}}
    @if($sessionTrends->isNotEmpty())
    <x-card class="mb-8">
        <x-card-header>
            <div class="flex items-center gap-3">
                <div class="p-2 rounded-lg bg-blue-100 text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900">اتجاهات الجلسات (آخر 6 أشهر)</h3>
            </div>
        </x-card-header>
        <x-card-body>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">الشهر</th>
                            <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">عدد الجلسات</th>
                            <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">متوسط التقييم</th>
                            <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">الاتجاه</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sessionTrends as $index => $trend)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-3 px-4 text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($trend->month)->locale('ar')->translatedFormat('F Y') }}
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-medium text-gray-900">{{ $trend->sessions_count }}</span>
                                        <div class="flex-1 max-w-xs">
                                            <x-progress :value="$trend->sessions_count" :max="$sessionTrends->max('sessions_count')" variant="primary" size="sm" />
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <x-badge variant="{{ $trend->avg_score >= 8 ? 'success' : ($trend->avg_score >= 6 ? 'warning' : 'error') }}">
                                        {{ number_format($trend->avg_score, 2) }}
                                    </x-badge>
                                </td>
                                <td class="py-3 px-4">
                                    @if($index > 0)
                                        @php
                                            $prevTrend = $sessionTrends[$index - 1];
                                            $change = $trend->sessions_count - $prevTrend->sessions_count;
                                        @endphp
                                        @if($change > 0)
                                            <div class="flex items-center gap-1 text-green-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                                </svg>
                                                <span class="text-xs font-medium">+{{ $change }}</span>
                                            </div>
                                        @elseif($change < 0)
                                            <div class="flex items-center gap-1 text-red-600">
                                                <svg class="w-4 h-4 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                                </svg>
                                                <span class="text-xs font-medium">{{ $change }}</span>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-1 text-gray-500">
                                                <span class="text-xs">—</span>
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-xs text-gray-400">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card-body>
    </x-card>
    @endif
</div>
@endsection
