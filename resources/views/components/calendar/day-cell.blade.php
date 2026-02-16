@props([
    'schedules' => [],
])

<div class="min-h-[80px] p-2 border-r border-b border-gray-200 bg-white hover:bg-gray-50/50 transition-colors duration-150">
    @foreach($schedules as $schedule)
        <x-calendar.schedule-item :schedule="$schedule" />
    @endforeach
</div>
