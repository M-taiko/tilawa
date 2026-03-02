@extends('layouts.app')
@section('title', 'إحصائيات الزوار')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">إحصائيات الزوار</h1>
            <p class="text-sm text-slate-500 mt-1">تحليل زيارات المصحف الكريم</p>
        </div>
        {{-- فلتر الفترة --}}
        <div class="flex items-center gap-2 bg-white rounded-xl border border-slate-200 p-1 shadow-sm">
            @foreach([7 => 'أسبوع', 30 => 'شهر', 90 => '3 أشهر', 365 => 'سنة'] as $d => $label)
            <a href="?days={{ $d }}"
               class="px-3 py-1.5 rounded-lg text-sm font-semibold transition-all
                      {{ $days == $d ? 'bg-primary-500 text-white shadow' : 'text-slate-600 hover:bg-slate-100' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>
    </div>

    {{-- بطاقات الإحصائيات الرئيسية --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <x-card class="p-5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-slate-800">{{ number_format($totalVisits) }}</div>
                    <div class="text-xs text-slate-500 mt-0.5">إجمالي الزيارات</div>
                </div>
            </div>
        </x-card>

        <x-card class="p-5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-slate-800">{{ number_format($uniqueVisitors) }}</div>
                    <div class="text-xs text-slate-500 mt-0.5">زوار فريدون</div>
                </div>
            </div>
        </x-card>

        <x-card class="p-5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-slate-800">{{ number_format($todayVisits) }}</div>
                    <div class="text-xs text-slate-500 mt-0.5">زيارات اليوم</div>
                </div>
            </div>
        </x-card>

        <x-card class="p-5">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-slate-800">{{ number_format($weekVisits) }}</div>
                    <div class="text-xs text-slate-500 mt-0.5">زيارات الأسبوع</div>
                </div>
            </div>
        </x-card>
    </div>

    {{-- الرسم البياني --}}
    <x-card class="p-5">
        <h2 class="text-base font-bold text-slate-700 mb-4">الزيارات اليومية</h2>
        <div style="height:220px;">
            <canvas id="visitsChart"></canvas>
        </div>
    </x-card>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- الدول --}}
        <x-card class="p-5">
            <h2 class="text-base font-bold text-slate-700 mb-4 flex items-center gap-2">
                <span>🌍</span> الزوار حسب الدولة
            </h2>
            @if($byCountry->isEmpty())
                <p class="text-sm text-slate-400 text-center py-8">لا توجد بيانات بعد</p>
            @else
            <div class="space-y-3">
                @php $maxVisits = $byCountry->first()->visits; @endphp
                @foreach($byCountry as $row)
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-2">
                            <span class="text-lg">{{ $row->country_code ? '🌐' : '🌍' }}</span>
                            <span class="text-sm font-medium text-slate-700">{{ $row->country_name ?? 'غير معروف' }}</span>
                            @if($row->country_code)
                            <span class="text-xs text-slate-400 font-mono">{{ $row->country_code }}</span>
                            @endif
                        </div>
                        <span class="text-sm font-bold text-slate-700">{{ number_format($row->visits) }}</span>
                    </div>
                    <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-blue-400 to-blue-600 rounded-full transition-all"
                             style="width: {{ $maxVisits > 0 ? round(($row->visits / $maxVisits) * 100) : 0 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </x-card>

        <div class="space-y-6">
            {{-- الأجهزة --}}
            <x-card class="p-5">
                <h2 class="text-base font-bold text-slate-700 mb-4 flex items-center gap-2">
                    <span>📱</span> نوع الجهاز
                </h2>
                @php
                    $deviceIcons  = ['mobile' => '📱', 'tablet' => '📟', 'desktop' => '🖥️'];
                    $deviceLabels = ['mobile' => 'موبايل', 'tablet' => 'تابلت', 'desktop' => 'كمبيوتر'];
                    $deviceColors = ['mobile' => 'from-emerald-400 to-emerald-600', 'tablet' => 'from-amber-400 to-amber-600', 'desktop' => 'from-blue-400 to-blue-600'];
                    $totalDevices = $byDevice->sum('visits');
                @endphp
                @if($byDevice->isEmpty())
                    <p class="text-sm text-slate-400 text-center py-4">لا توجد بيانات</p>
                @else
                <div class="space-y-3">
                    @foreach($byDevice as $row)
                    @php $pct = $totalDevices > 0 ? round(($row->visits / $totalDevices) * 100) : 0; @endphp
                    <div class="flex items-center gap-3">
                        <span class="text-xl w-7 text-center">{{ $deviceIcons[$row->device_type] ?? '💻' }}</span>
                        <div class="flex-1">
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium text-slate-700">{{ $deviceLabels[$row->device_type] ?? $row->device_type }}</span>
                                <span class="text-sm font-bold text-slate-700">{{ $pct }}%</span>
                            </div>
                            <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r {{ $deviceColors[$row->device_type] ?? 'from-slate-400 to-slate-600' }} rounded-full"
                                     style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                        <span class="text-xs text-slate-500 w-12 text-left">{{ number_format($row->visits) }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </x-card>

            {{-- الصفحات الأكثر زيارة --}}
            <x-card class="p-5">
                <h2 class="text-base font-bold text-slate-700 mb-4 flex items-center gap-2">
                    <span>📄</span> الصفحات الأكثر زيارة
                </h2>
                @if($topPages->isEmpty())
                    <p class="text-sm text-slate-400 text-center py-4">لا توجد بيانات</p>
                @else
                <div class="space-y-2">
                    @foreach($topPages as $i => $row)
                    <div class="flex items-center gap-3 py-1.5 border-b border-slate-50 last:border-0">
                        <span class="w-5 h-5 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-500">{{ $i + 1 }}</span>
                        <span class="flex-1 text-sm text-slate-700 font-mono truncate" dir="ltr">{{ $row->page }}</span>
                        <span class="text-sm font-bold text-primary-600">{{ number_format($row->visits) }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </x-card>
        </div>
    </div>

</div>

<script>
const ctx = document.getElementById('visitsChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartLabels) !!},
        datasets: [{
            label: 'الزيارات',
            data: {!! json_encode($chartData) !!},
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59,130,246,0.08)',
            borderWidth: 2,
            pointRadius: 3,
            pointBackgroundColor: '#3b82f6',
            fill: true,
            tension: 0.4,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 11 } } },
            y: { beginAtZero: true, ticks: { precision: 0, font: { size: 11 } } }
        }
    }
});
</script>
@endsection
