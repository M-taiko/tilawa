@props([
    'label',
    'value',
    'icon' => null,
    'iconColor' => 'blue',
    'progress' => null,
    'progressVariant' => 'primary'
])

@php
    $iconColors = [
        'blue' => 'text-blue-500',
        'purple' => 'text-purple-500',
        'green' => 'text-green-500',
        'orange' => 'text-orange-500',
        'cyan' => 'text-cyan-500',
        'yellow' => 'text-yellow-500',
        'red' => 'text-red-500',
        'gray' => 'text-gray-500',
    ];
@endphp

<div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
    <div class="flex items-center gap-2 mb-2">
        @if($icon)
            <div class="{{ $iconColors[$iconColor] ?? $iconColors['blue'] }}">
                {!! $icon !!}
            </div>
        @endif
        <p class="text-xs text-gray-500 font-medium">{{ $label }}</p>
    </div>
    <p class="text-2xl font-bold text-gray-900">{{ $value }}</p>

    @if($progress !== null)
        <div class="mt-2">
            <x-progress :value="$progress" :variant="$progressVariant" size="sm" />
        </div>
    @endif

    @if($slot->isNotEmpty())
        <div class="mt-2">
            {{ $slot }}
        </div>
    @endif
</div>
