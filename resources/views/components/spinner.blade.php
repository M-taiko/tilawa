@props([
    'size' => 'md', // sm, md, lg, xl
    'color' => 'blue',
    'text' => null,
])

@php
$sizes = [
    'sm' => 'h-4 w-4 border-2',
    'md' => 'h-8 w-8 border-3',
    'lg' => 'h-12 w-12 border-4',
    'xl' => 'h-16 w-16 border-4',
];

$colors = [
    'blue' => 'border-blue-500 border-t-transparent',
    'green' => 'border-green-500 border-t-transparent',
    'red' => 'border-red-500 border-t-transparent',
    'yellow' => 'border-yellow-500 border-t-transparent',
    'purple' => 'border-purple-500 border-t-transparent',
    'gray' => 'border-gray-500 border-t-transparent',
    'white' => 'border-white border-t-transparent',
];

$sizeClass = $sizes[$size] ?? $sizes['md'];
$colorClass = $colors[$color] ?? $colors['blue'];
@endphp

<div class="flex flex-col items-center justify-center gap-3 {{ $attributes->get('class') }}" {{ $attributes->except('class') }}>
    <div class="animate-spin rounded-full {{ $sizeClass }} {{ $colorClass }}"></div>
    @if($text)
        <p class="text-sm text-gray-600 font-medium">{{ $text }}</p>
    @elseif(isset($slot) && !empty(trim($slot)))
        {{ $slot }}
    @endif
</div>
