@props([
    'schedule',
    'class' => null,
])

@php
$studyClass = $schedule->studyClass ?? $class;
$group = $studyClass->group ?? 'men';
$isActive = $schedule->is_active ?? true;

$theme = [
    'men' => [
        'primary' => '#3b82f6',
        'light' => '#eff6ff',
        'dark' => '#1e40af',
    ],
    'women' => [
        'primary' => '#ec4899',
        'light' => '#fdf2f8',
        'dark' => '#be185d',
    ],
    'kids' => [
        'primary' => '#10b981',
        'light' => '#ecfdf5',
        'dark' => '#047857',
    ],
];

$t = $theme[$group] ?? $theme['men'];
@endphp

<div
    class="group relative bg-white rounded-lg overflow-hidden cursor-move mb-2.5 transition-all duration-200 hover:shadow-lg {{ $isActive ? 'shadow-sm' : 'opacity-50' }}"
    style="border: 1px solid {{ $t['primary'] }}20;"
    draggable="true"
    data-schedule-id="{{ $schedule->id }}"
    data-current-day="{{ $schedule->day_of_week }}"
    data-current-time="{{ $schedule->start_time }}"
    ondragstart="handleDragStart(event)"
    ondragend="handleDragEnd(event)"
    onclick="showScheduleModal({{ $schedule->id }})"
    role="button"
    tabindex="0"
    aria-label="عرض تفاصيل {{ $studyClass->name }}"
>
    {{-- Color accent bar --}}
    <div class="h-1" style="background: {{ $t['primary'] }};"></div>

    {{-- Content --}}
    <div class="p-3">
        {{-- Header --}}
        <div class="flex items-start gap-2.5 mb-2.5">
            <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center text-white shadow-sm"
                 style="background: {{ $t['primary'] }};">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="font-bold text-sm text-slate-900 mb-1 line-clamp-1">
                    {{ $studyClass->name }}
                </h4>
                @if($studyClass->track ?? null)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-semibold"
                          style="background: {{ $t['light'] }}; color: {{ $t['dark'] }};">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        {{ $studyClass->track === 'memorization' ? 'حفظ' : 'أساسيات' }}
                    </span>
                @endif
            </div>
            @if(!$isActive)
                <span class="flex-shrink-0 px-1.5 py-0.5 rounded bg-red-100 text-red-700 text-xs font-semibold">
                    متوقف
                </span>
            @endif
        </div>

        {{-- Time & Duration --}}
        <div class="flex items-center gap-2 mb-2.5 text-xs">
            <div class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-md" style="background: {{ $t['light'] }};">
                <svg class="w-3.5 h-3.5" style="color: {{ $t['primary'] }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-bold" style="color: {{ $t['dark'] }};">
                    {{ date('g:i A', strtotime($schedule->start_time)) }}
                </span>
            </div>

            @if($schedule->duration_minutes ?? 0)
                <div class="flex items-center gap-1 px-2.5 py-1.5 rounded-md bg-slate-100">
                    <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span class="font-semibold text-slate-700">{{ $schedule->duration_minutes }} د</span>
                </div>
            @endif
        </div>

        {{-- Teacher --}}
        @if($studyClass->teacher ?? null)
            <div class="flex items-center gap-2 px-2.5 py-2 rounded-md bg-slate-50">
                <div class="flex-shrink-0 w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold"
                     style="background: {{ $t['primary'] }};">
                    {{ mb_substr($studyClass->teacher->name, 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-[10px] text-slate-500">المعلم</div>
                    <div class="font-semibold text-xs text-slate-900 truncate">
                        {{ $studyClass->teacher->name }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Hover overlay --}}
    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"
         style="background: linear-gradient(135deg, {{ $t['primary'] }}05, transparent);"></div>
</div>
