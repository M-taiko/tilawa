@extends('layouts.app')

@section('title', 'تقاريري')

@section('content')
    {{-- Compact Hero Header --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div
                    class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">تقاريري الشخصية</h1>
                    <p class="text-sm text-gray-500 mt-1">متابعة أدائي وإنجازات طلابي</p>
                </div>
            </div>
            <x-button href="{{ route('teacher.dashboard') }}" variant="outline" size="sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 001 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 00-1-1H9a1 1 0 00-1 1v4a1 1 0 001 1h2m7 11v-5h-2.648" />
                </svg>
                لوحة التحكم
            </x-button>
        </div>
    </div>

    {{-- Date Range Filter (New Layout) --}}
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-8">
        <div class="flex flex-col xl:flex-row gap-6">
            <div class="xl:w-[55%]">
                <div class="flex items-center justify-between mb-3">
                    <label class="text-sm font-semibold text-gray-700">الفترة السريعة</label>
                    <span class="text-xs text-gray-500">اختر فترة جاهزة</span>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ request()->fullUrlWithQuery(['start_date' => now()->startOfMonth()->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}"
                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-full {{ request('start_date') === now()->startOfMonth()->format('Y-m-d') ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-50 text-gray-700 border border-gray-200 hover:bg-gray-100' }} transition-all">
                        هذا الشهر
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['start_date' => now()->subMonths(3)->startOfMonth()->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}"
                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-full {{ request('start_date') === now()->subMonths(3)->startOfMonth()->format('Y-m-d') ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-50 text-gray-700 border border-gray-200 hover:bg-gray-100' }} transition-all">
                        3 أشهر
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['start_date' => now()->subMonths(6)->startOfMonth()->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}"
                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-full {{ request('start_date') === now()->subMonths(6)->startOfMonth()->format('Y-m-d') ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-50 text-gray-700 border border-gray-200 hover:bg-gray-100' }} transition-all">
                        6 أشهر
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['start_date' => now()->startOfYear()->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}"
                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-full {{ request('start_date') === now()->startOfYear()->format('Y-m-d') ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-50 text-gray-700 border border-gray-200 hover:bg-gray-100' }} transition-all">
                        هذه السنة
                    </a>
                </div>
            </div>

            <div class="xl:w-[45%] xl:border-r xl:border-gray-200 xl:pr-6">
                <div class="flex items-center justify-between mb-3">
                    <label class="text-sm font-semibold text-gray-700">تحديد مخصص</label>
                    <span class="text-xs text-gray-500">اختر نطاقاً يدوياً</span>
                </div>
                <form method="GET" action="{{ route('teacher.reports.index') }}"
                    class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-end">
                    <div>
                        <x-input name="start_date" label="من تاريخ" type="date" :value="$startDate">
                            <x-slot name="icon">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </x-slot>
                        </x-input>
                    </div>
                    <div>
                        <x-input name="end_date" label="إلى تاريخ" type="date" :value="$endDate">
                            <x-slot name="icon">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </x-slot>
                        </x-input>
                    </div>
                    <div class="sm:col-span-2">
                        <x-button type="submit" variant="primary" class="w-full">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            تطبيق
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Key Stats with Compact Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2.5 bg-blue-100 rounded-lg">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">عدد الجلسات</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats->sessions_count ?? 0 }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2.5 bg-green-100 rounded-lg">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">الطلاب النشطون</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats->active_students ?? 0 }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2.5 bg-purple-100 rounded-lg">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">آيات محفوظة</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats->total_new_ayahs ?? 0 }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="p-2.5 bg-amber-100 rounded-lg">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">متوسط التقييم</p>
                <p class="text-2xl font-bold text-gray-900">
                    {{ $stats->avg_score ? number_format($stats->avg_score, 1) : '0.0' }}
                </p>
            </div>
        </div>
    </div>
    </div>

    {{-- Charts Section --}}
    <div class="space-y-6 mb-8">
        {{-- Session Trends Chart --}}
        @if($sessionTrends->isNotEmpty())
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">الجلسات الشهرية</h3>
                    <p class="text-sm text-gray-500 mt-1">آخر 6 أشهر - الجلسات ومتوسط التقييم</p>
                </div>
                <div class="p-6">
                    <div class="h-[320px]">
                        <canvas id="sessionTrendsChart"></canvas>
                        <script type="application/json" id="sessionTrendsData">{!! $sessionTrends->toJson() !!}</script>
                    </div>
                </div>
            </div>
        @endif

        {{-- Two Columns: Top Students + Inactive Students --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Top Performing Students --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">أفضل 10 طلاب</h3>
                    <p class="text-sm text-gray-500 mt-1">أكثرهم حفظاً</p>
                </div>
                <div class="p-6">
                    @forelse($topStudents as $student)
                        <div
                            class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex-shrink-0 w-8 h-8 rounded-full {{ $loop->index === 0 ? 'bg-amber-500' : ($loop->index === 1 ? 'bg-gray-400' : ($loop->index === 2 ? 'bg-amber-700' : 'bg-gray-200')) }} flex items-center justify-center text-white font-bold text-sm">
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
                        <p class="text-gray-500 text-center py-8">لا توجد بيانات للفترة المحددة</p>
                    @endforelse
                </div>
            </div>

            {{-- Inactive Students (Needing Attention) --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">طلاب يحتاجون متابعة</h3>
                    <p class="text-sm text-gray-500 mt-1">لم يحضروا منذ 14+ يوم</p>
                </div>
                <div class="p-6">
                    @forelse($inactiveStudents as $student)
                        <div
                            class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $student->name }}</p>
                                <p class="text-xs text-gray-500">{{ $student->class?->name ?? 'غير معيّن' }}</p>
                            </div>
                            <div class="text-left">
                                @if($student->last_session_date)
                                    <p class="text-sm text-red-600 font-semibold">
                                        {{ \Carbon\Carbon::parse($student->last_session_date)->diffForHumans() }}
                                    </p>
                                    <p class="text-xs text-gray-500">آخر جلسة</p>
                                @else
                                    <p class="text-sm text-red-600 font-semibold">لم يحضر أبداً</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8">جميع الطلاب نشطون 🎉</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Additional Analytics: Average Scores by Student + Attendance Breakdown --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Average Scores by Student (Horizontal Bar Chart) --}}
            @if($studentScores->isNotEmpty())
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900">متوسط التقييم لكل طالب</h3>
                        <p class="text-sm text-gray-500 mt-1">أعلى 15 طالباً</p>
                    </div>
                    <div class="p-6">
                        <div class="h-[320px]">
                            <canvas id="studentScoresChart"></canvas>
                            <script type="application/json" id="studentScoresData">{!! $studentScores->toJson() !!}</script>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Attendance Breakdown (Pie Chart) --}}
            @if($attendanceBreakdown && $attendanceBreakdown->total_sessions > 0)
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900">توزيع الحضور والغياب</h3>
                        <p class="text-sm text-gray-500 mt-1">إجمالي {{ $attendanceBreakdown->total_sessions }} جلسة</p>
                    </div>
                    <div class="p-6 flex items-center justify-center">
                        <div class="h-[320px] w-full">
                            <canvas id="attendanceBreakdownChart"></canvas>
                            <script type="application/json" id="attendanceBreakdownData">{!! json_encode($attendanceBreakdown) !!}</script>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Chart.js for All Charts --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Chart.defaults.color = '#64748b';
            Chart.defaults.font.family = "'Tajawal', sans-serif";

            // Session Trends Chart
            const trendsCtx = document.getElementById('sessionTrendsChart');
            const trendsDataRaw = document.getElementById('sessionTrendsData')?.textContent || '[]';
            const trendsData = JSON.parse(trendsDataRaw);
            if (trendsCtx && Array.isArray(trendsData) && trendsData.length) {
                const trendsLabels = trendsData.map(item => {
                    const date = new Date(item.month);
                    return date.toLocaleDateString('ar-SA', {
                        year: 'numeric',
                        month: 'short'
                    });
                });

                const sessionCounts = trendsData.map(item => item.sessions_count);
                const avgScores = trendsData.map(item => parseFloat(item.avg_score || 0).toFixed(1));

                new Chart(trendsCtx, {
                    type: 'line',
                    data: {
                        labels: trendsLabels,
                        datasets: [{
                            label: 'عدد الجلسات',
                            data: sessionCounts,
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 2,
                            pointRadius: 3,
                            pointBackgroundColor: 'rgb(59, 130, 246)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            yAxisID: 'y'
                        },
                        {
                            label: 'متوسط التقييم',
                            data: avgScores,
                            borderColor: 'rgb(245, 158, 11)',
                            backgroundColor: 'rgba(245, 158, 11, 0.1)',
                            fill: true,
                            tension: 0.4,
                            borderWidth: 2,
                            pointRadius: 3,
                            pointBackgroundColor: 'rgb(245, 158, 11)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            yAxisID: 'y1'
                        }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    font: {
                                        size: 12
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                type: 'linear',
                                display: true,
                                position: 'left',
                                title: {
                                    display: true,
                                    text: 'عدد الجلسات',
                                    font: {
                                        size: 12
                                    }
                                },
                                grid: {
                                    color: '#f1f5f9'
                                }
                            },
                            y1: {
                                type: 'linear',
                                display: true,
                                position: 'right',
                                title: {
                                    display: true,
                                    text: 'متوسط التقييم',
                                    font: {
                                        size: 12
                                    }
                                },
                                grid: {
                                    drawOnChartArea: false,
                                },
                                max: 10
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            // Student Scores Chart (Horizontal Bar)
            const scoresCtx = document.getElementById('studentScoresChart');
            const scoresDataRaw = document.getElementById('studentScoresData')?.textContent || '[]';
            const scoresData = JSON.parse(scoresDataRaw);
            if (scoresCtx && Array.isArray(scoresData) && scoresData.length) {
                const studentNames = scoresData.map(item => item.name);
                const studentAvgScores = scoresData.map(item => parseFloat(item.avg_score).toFixed(1));

                new Chart(scoresCtx, {
                    type: 'bar',
                    data: {
                        labels: studentNames,
                        datasets: [{
                            label: 'متوسط التقييم',
                            data: studentAvgScores,
                            backgroundColor: studentAvgScores.map(score => {
                                if (score >= 8) return 'rgba(16, 185, 129, 0.8)';
                                if (score >= 6) return 'rgba(245, 158, 11, 0.8)';
                                return 'rgba(239, 68, 68, 0.8)';
                            }),
                            borderColor: studentAvgScores.map(score => {
                                if (score >= 8) return 'rgb(16, 185, 129)';
                                if (score >= 6) return 'rgb(245, 158, 11)';
                                return 'rgb(239, 68, 68)';
                            }),
                            borderWidth: 2,
                            borderRadius: 6,
                            barThickness: 24
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                padding: 10,
                                cornerRadius: 6,
                                displayColors: true
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                max: 10,
                                title: {
                                    display: true,
                                    text: 'المتوسط (من 10)',
                                    font: {
                                        size: 12
                                    }
                                },
                                grid: {
                                    color: '#f1f5f9'
                                }
                            },
                            y: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

            // Attendance Breakdown Chart (Doughnut)
            const attendanceCtx = document.getElementById('attendanceBreakdownChart');
            const attendanceDataRaw = document.getElementById('attendanceBreakdownData')?.textContent || 'null';
            const attendanceData = JSON.parse(attendanceDataRaw);
            if (attendanceCtx && attendanceData && attendanceData.total_sessions > 0) {
                new Chart(attendanceCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['حاضر', 'غائب', 'معتذر'],
                        datasets: [{
                            label: 'عدد الجلسات',
                            data: [
                                attendanceData.present_count,
                                attendanceData.absent_count,
                                attendanceData.excused_count
                            ],
                            backgroundColor: [
                                'rgba(16, 185, 129, 0.85)',
                                'rgba(239, 68, 68, 0.85)',
                                'rgba(245, 158, 11, 0.85)'
                            ],
                            borderColor: [
                                'rgb(16, 185, 129)',
                                'rgb(239, 68, 68)',
                                'rgb(245, 158, 11)'
                            ],
                            borderWidth: 3,
                            hoverOffset: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '65%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    font: {
                                        size: 13,
                                        weight: '500'
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                padding: 12,
                                cornerRadius: 8,
                                callbacks: {
                                    label: function (context) {
                                        const label = context.label || '';
                                        const rawValue = context.raw;
                                        const total = attendanceData.total_sessions;
                                        const percentage = total > 0 ? ((rawValue / total) * 100)
                                            .toFixed(1) : 0;
                                        return label + ': ' + rawValue + ' جلسة (' + percentage + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endsection
