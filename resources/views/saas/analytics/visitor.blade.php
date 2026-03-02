@extends('layouts.app')
@section('title', 'تفاصيل الزائر – ' . $ip)

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        @php
            $backRoute = auth()->user()->isSaasAdmin() ? 'saas.analytics' : 'admin.analytics';
        @endphp
        <a href="{{ route($backRoute) }}"
           class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-500 hover:text-slate-800 hover:border-slate-300 transition-all shadow-sm">
            →
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800" dir="ltr">{{ $ip }}</h1>
            <p class="text-sm text-slate-500 mt-0.5">تفاصيل الزائر</p>
        </div>
    </div>

    {{-- معلومات الزائر --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- بطاقة المعلومات الجغرافية --}}
        <x-card class="p-5">
            <h2 class="text-base font-bold text-slate-700 mb-4 flex items-center gap-2">
                <span>🌍</span> المعلومات الجغرافية
            </h2>
            <dl class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-slate-50">
                    <dt class="text-sm text-slate-500">الدولة</dt>
                    <dd class="text-sm font-semibold text-slate-800">
                        {{ $summary->country_name ?? '—' }}
                        @if($summary->country_code)
                        <span class="text-xs text-slate-400 font-mono ml-1">({{ $summary->country_code }})</span>
                        @endif
                    </dd>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-50">
                    <dt class="text-sm text-slate-500">المدينة</dt>
                    <dd class="text-sm font-semibold text-slate-800">{{ $summary->city ?? '—' }}</dd>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-50">
                    <dt class="text-sm text-slate-500">عنوان IP</dt>
                    <dd class="text-sm font-mono text-slate-800" dir="ltr">{{ $summary->ip }}</dd>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-50">
                    <dt class="text-sm text-slate-500">نوع الجهاز</dt>
                    <dd class="text-sm font-semibold text-slate-800">
                        @php $deviceLabels = ['mobile' => '📱 موبايل', 'tablet' => '📟 تابلت', 'desktop' => '🖥️ كمبيوتر']; @endphp
                        {{ $deviceLabels[$summary->device_type] ?? $summary->device_type ?? '—' }}
                    </dd>
                </div>
            </dl>
        </x-card>

        {{-- بطاقة إحصائيات الزيارة --}}
        <x-card class="p-5">
            <h2 class="text-base font-bold text-slate-700 mb-4 flex items-center gap-2">
                <span>📊</span> إحصائيات الزيارة
            </h2>
            <dl class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-slate-50">
                    <dt class="text-sm text-slate-500">إجمالي الزيارات</dt>
                    <dd>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-blue-50 text-blue-700">
                            {{ number_format($summary->visit_count) }}
                        </span>
                    </dd>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-50">
                    <dt class="text-sm text-slate-500">أول زيارة</dt>
                    <dd class="text-sm text-slate-800" dir="ltr">
                        {{ \Carbon\Carbon::parse($summary->first_visit)->format('Y-m-d H:i') }}
                    </dd>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-slate-50">
                    <dt class="text-sm text-slate-500">آخر زيارة</dt>
                    <dd class="text-sm text-slate-800" dir="ltr">
                        {{ \Carbon\Carbon::parse($summary->last_visit)->format('Y-m-d H:i') }}
                    </dd>
                </div>
                <div class="flex justify-between items-center py-2">
                    <dt class="text-sm text-slate-500">منذ</dt>
                    <dd class="text-sm text-slate-500">
                        {{ \Carbon\Carbon::parse($summary->last_visit)->diffForHumans() }}
                    </dd>
                </div>
            </dl>
        </x-card>
    </div>

    {{-- User Agent --}}
    @if($summary->user_agent)
    <x-card class="p-5">
        <h2 class="text-base font-bold text-slate-700 mb-3 flex items-center gap-2">
            <span>🔎</span> معلومات المتصفح
        </h2>
        <p class="text-xs font-mono text-slate-500 bg-slate-50 rounded-lg p-3 break-all" dir="ltr">
            {{ $summary->user_agent }}
        </p>
    </x-card>
    @endif

    {{-- الصفحات المُزارة --}}
    <x-card class="p-5">
        <h2 class="text-base font-bold text-slate-700 mb-4 flex items-center gap-2">
            <span>📄</span> الصفحات التي زارها
        </h2>
        @if($pages->isEmpty())
            <p class="text-sm text-slate-400 text-center py-4">لا توجد بيانات</p>
        @else
        <div class="space-y-2">
            @php $maxP = $pages->first()->visits; @endphp
            @foreach($pages as $i => $row)
            <div class="flex items-center gap-3 py-1.5">
                <span class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-500 flex-shrink-0">{{ $i + 1 }}</span>
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between mb-1">
                        <span class="text-sm text-slate-700 font-mono truncate" dir="ltr">{{ $row->page }}</span>
                        <span class="text-sm font-bold text-primary-600 ml-2 flex-shrink-0">{{ number_format($row->visits) }}</span>
                    </div>
                    <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-primary-400 to-primary-600 rounded-full"
                             style="width: {{ $maxP > 0 ? round(($row->visits / $maxP) * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </x-card>

    {{-- سجل الزيارات --}}
    <x-card class="p-5">
        <h2 class="text-base font-bold text-slate-700 mb-4 flex items-center gap-2">
            <span>🕐</span> سجل الزيارات
            <span class="text-xs font-normal text-slate-400 mr-auto">{{ $logs->total() }} سجل</span>
        </h2>

        @if($logs->isEmpty())
            <p class="text-sm text-slate-400 text-center py-4">لا توجد سجلات</p>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 text-right">
                        <th class="pb-3 text-xs font-semibold text-slate-500">التاريخ والوقت</th>
                        <th class="pb-3 text-xs font-semibold text-slate-500">الصفحة</th>
                        <th class="pb-3 text-xs font-semibold text-slate-500">الجهاز</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($logs as $log)
                    @php $deviceIcons = ['mobile' => '📱', 'tablet' => '📟', 'desktop' => '🖥️']; @endphp
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="py-2.5 text-xs text-slate-500" dir="ltr">
                            {{ \Carbon\Carbon::parse($log->visited_at)->format('Y-m-d H:i:s') }}
                        </td>
                        <td class="py-2.5 font-mono text-xs text-slate-700" dir="ltr">
                            /{{ $log->page }}
                        </td>
                        <td class="py-2.5 text-center">
                            <span title="{{ $log->device_type }}">{{ $deviceIcons[$log->device_type] ?? '💻' }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
        <div class="mt-4 flex justify-center">
            {{ $logs->links() }}
        </div>
        @endif
        @endif
    </x-card>

</div>
@endsection
