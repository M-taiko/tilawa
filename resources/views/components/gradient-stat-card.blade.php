@props([
    'label',
    'value',
    'gradient' => 'blue',
    'icon' => null,
])

@php
$gradients = [
    'blue' => [
        'bg' => 'from-blue-50 to-blue-100',
        'border' => 'border-blue-200',
        'text' => 'text-blue-600',
        'value' => 'text-blue-900',
        'icon-bg' => 'bg-blue-200',
        'icon-text' => 'text-blue-700',
    ],
    'green' => [
        'bg' => 'from-green-50 to-green-100',
        'border' => 'border-green-200',
        'text' => 'text-green-600',
        'value' => 'text-green-900',
        'icon-bg' => 'bg-green-200',
        'icon-text' => 'text-green-700',
    ],
    'purple' => [
        'bg' => 'from-purple-50 to-purple-100',
        'border' => 'border-purple-200',
        'text' => 'text-purple-600',
        'value' => 'text-purple-900',
        'icon-bg' => 'bg-purple-200',
        'icon-text' => 'text-purple-700',
    ],
    'gray' => [
        'bg' => 'from-gray-50 to-gray-100',
        'border' => 'border-gray-200',
        'text' => 'text-gray-600',
        'value' => 'text-gray-900',
        'icon-bg' => 'bg-gray-200',
        'icon-text' => 'text-gray-700',
    ],
    'red' => [
        'bg' => 'from-red-50 to-red-100',
        'border' => 'border-red-200',
        'text' => 'text-red-600',
        'value' => 'text-red-900',
        'icon-bg' => 'bg-red-200',
        'icon-text' => 'text-red-700',
    ],
    'yellow' => [
        'bg' => 'from-yellow-50 to-yellow-100',
        'border' => 'border-yellow-200',
        'text' => 'text-yellow-600',
        'value' => 'text-yellow-900',
        'icon-bg' => 'bg-yellow-200',
        'icon-text' => 'text-yellow-700',
    ],
];

$style = $gradients[$gradient] ?? $gradients['blue'];
@endphp

<div class="bg-gradient-to-br {{ $style['bg'] }} border {{ $style['border'] }} rounded-xl p-4">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium {{ $style['text'] }} mb-1">{{ $label }}</p>
            <p class="text-2xl font-bold {{ $style['value'] }}">{{ $value }}</p>
        </div>
        @if($icon || $slot->isNotEmpty())
        <div class="p-3 {{ $style['icon-bg'] }} rounded-lg">
            <div class="{{ $style['icon-text'] }} [&>svg]:w-6 [&>svg]:h-6">
                @if($icon)
                    {!! $icon !!}
                @else
                    {{ $slot }}
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
