@props([
    'day',
    'dayName',
    'scheduleCount' => 0,
])

<div class="text-center py-4 px-2 border-r border-white/20">
    <div class="font-bold text-base mb-1">{{ $dayName }}</div>
    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/20 backdrop-blur-sm">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <span class="text-xs font-medium">{{ $scheduleCount }}</span>
    </div>
</div>
