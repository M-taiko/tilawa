@extends('layouts.app')

@section('title', 'التقويم الأسبوعي')

@push('styles')
<style>
    /* Modal full-screen fix */
    #scheduleModal {
        position: fixed !important;
        inset: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        margin: 0 !important;
        overflow-y: auto !important;
    }

    /* Modern Calendar Design */
    .calendar-wrapper {
        background: #ffffff;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }

    .calendar-header {
        background: linear-gradient(180deg, #f9fafb 0%, #f3f4f6 100%);
        border-bottom: 1px solid #e5e7eb;
        position: sticky;
        top: 0;
        z-index: 40;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: 85px repeat(7, 1fr);
        background: #fafafa;
    }

    .calendar-time-cell {
        position: sticky;
        left: 0;
        z-index: 10;
        padding: 12px 6px;
        text-align: center;
        background: linear-gradient(to left, #f9fafb, #ffffff);
        border-left: 1px solid #e5e7eb;
        border-bottom: 1px solid #f3f4f6;
        font-feature-settings: "tnum";
        font-variant-numeric: tabular-nums;
    }

    .calendar-day-cell {
        min-height: 100px;
        padding: 8px;
        border-left: 1px solid #f0f0f0;
        border-bottom: 1px solid #f3f4f6;
        background: #ffffff;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .calendar-day-cell:hover {
        background: #fafbfc;
    }

    .calendar-day-cell.drag-over {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        border-left: 3px solid #3b82f6;
        box-shadow: inset 0 0 0 2px rgba(59, 130, 246, 0.15);
    }

    .schedule-item-dragging {
        opacity: 0.3;
        transform: scale(0.95) rotate(1deg);
        transition: all 0.2s ease;
    }

    @media (max-width: 1024px) {
        .calendar-grid {
            grid-template-columns: 80px repeat(7, minmax(120px, 1fr));
        }

        .calendar-wrapper {
            overflow-x: auto;
        }
    }


    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-slide-in {
        animation: slideIn 0.3s ease-out;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-1">📅 التقويم الأسبوعي</h1>
            <p class="text-gray-600">جدول المواعيد الأسبوعية للحلقات</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.schedules.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                قائمة المواعيد
            </a>
            <a href="{{ route('admin.schedules.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 border border-blue-600 rounded-lg text-sm font-semibold text-white hover:bg-blue-700 hover:border-blue-700 transition-all shadow-sm hover:shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                إضافة موعد
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 animate-slide-in">
        <x-gradient-stat-card label="إجمالي المواعيد" :value="$stats['total']" gradient="blue">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </x-gradient-stat-card>

        <x-gradient-stat-card label="المواعيد النشطة" :value="$stats['active']" gradient="green">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </x-gradient-stat-card>

        @php
            $busiestDay = $stats['busiest_day'] ?? 'sunday';
            $busiestCount = $stats['by_day'][$busiestDay] ?? 0;
        @endphp

        <x-gradient-stat-card
            label="اليوم الأكثر ازدحاماً"
            :value="$dayNames[$busiestDay] ?? '-'"
            gradient="purple"
            :subtitle="$busiestCount . ' موعد'"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
            </svg>
        </x-gradient-stat-card>

        <x-gradient-stat-card label="إجمالي الساعات" :value="$stats['total_hours'] . ' س'" gradient="yellow">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </x-gradient-stat-card>
    </div>

    {{-- Filter Bar --}}
    <div class="animate-slide-in">
        <x-calendar.filter-bar :groups="$groups" :tracks="$tracks" />
    </div>

    {{-- Legend --}}
    <div class="flex flex-wrap items-center gap-4 px-4 py-3 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl border border-blue-100">
        <span class="text-sm font-medium text-gray-700">المجموعات:</span>
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 rounded border-r-4 border-blue-500 bg-gradient-to-br from-blue-50 to-blue-100"></div>
            <span class="text-sm text-gray-600">رجال</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 rounded border-r-4 border-pink-500 bg-gradient-to-br from-pink-50 to-pink-100"></div>
            <span class="text-sm text-gray-600">نساء</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-4 h-4 rounded border-r-4 border-emerald-500 bg-gradient-to-br from-emerald-50 to-emerald-100"></div>
            <span class="text-sm text-gray-600">أطفال</span>
        </div>
    </div>

    {{-- Calendar Grid --}}
    @if($stats['total'] > 0)
        <div class="calendar-wrapper animate-slide-in">
            <div class="calendar-grid">
                {{-- Header Row --}}
                <div class="calendar-header flex items-center justify-center py-3 px-2 border-l border-gray-200">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                @foreach(['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'] as $day)
                    <div class="calendar-header text-center py-3 px-2 border-l border-gray-200">
                        <div class="font-semibold text-sm text-gray-900 mb-1.5">{{ $dayNames[$day] }}</div>
                        <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-xs font-semibold">{{ count($schedules[$day]) }}</span>
                        </div>
                    </div>
                @endforeach

                {{-- Time Rows --}}
                @php
                    $timeSlots = [
                        '06:00' => '6 ص', '07:00' => '7 ص', '08:00' => '8 ص', '09:00' => '9 ص',
                        '10:00' => '10 ص', '11:00' => '11 ص', '12:00' => '12 م', '13:00' => '1 م',
                        '14:00' => '2 م', '15:00' => '3 م', '16:00' => '4 م', '17:00' => '5 م',
                        '18:00' => '6 م', '19:00' => '7 م', '20:00' => '8 م', '21:00' => '9 م',
                        '22:00' => '10 م', '23:00' => '11 م'
                    ];
                @endphp

                @foreach($timeSlots as $time => $label)
                    {{-- Time Cell --}}
                    <div class="calendar-time-cell">
                        <div class="text-xs font-semibold text-gray-900">{{ $label }}</div>
                        <div class="text-xs text-gray-400 mt-0.5" style="font-size: 10px;">{{ $time }}</div>
                    </div>

                    {{-- Day Cells --}}
                    @foreach(['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'] as $day)
                        <div class="calendar-day-cell"
                             data-day="{{ $day }}"
                             data-time="{{ $time }}"
                             ondrop="handleDrop(event)"
                             ondragover="handleDragOver(event)"
                             ondragenter="handleDragEnter(event)"
                             ondragleave="handleDragLeave(event)">
                            @php
                                $hour = (int)substr($time, 0, 2);
                                $daySchedules = $schedules[$day] ?? [];

                                // Filter schedules for this hour
                                $hourSchedules = collect($daySchedules)->filter(function($s) use ($hour) {
                                    if (!$s || !isset($s->start_time)) return false;
                                    $startHour = (int)date('H', strtotime($s->start_time));
                                    return $startHour == $hour;
                                });
                            @endphp

                            @foreach($hourSchedules as $schedule)
                                <x-calendar.schedule-item :schedule="$schedule" />
                            @endforeach
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    @else
        {{-- Empty State --}}
        <div class="text-center py-16 bg-white rounded-2xl border-2 border-dashed border-gray-300">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-400 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">لا توجد مواعيد</h3>
            <p class="text-gray-600 mb-6">ابدأ بإضافة مواعيد جديدة للحلقات</p>
            <x-button href="{{ route('admin.schedules.create') }}" variant="primary">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                إضافة موعد جديد
            </x-button>
        </div>
    @endif
</div>
@endsection

{{-- Schedule Details Modal - Outside content to prevent container constraints --}}
<x-modal id="scheduleModal" title="تفاصيل الموعد" size="xl" closeFunction="closeScheduleModal()">
    <div id="scheduleModalContent"></div>
</x-modal>

@push('scripts')
<script src="{{ asset('js/calendar.js') }}"></script>
@endpush
