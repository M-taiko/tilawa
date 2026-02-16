@props([
    'label',
    'value',
    'subtitle' => null,
    'gradient' => 'blue',
    'icon' => null,
])

@php
$gradients = [
    'blue' => 'from-blue-500 to-blue-600 text-blue-100',
    'green' => 'from-green-500 to-green-600 text-green-100',
    'purple' => 'from-purple-500 to-purple-600 text-purple-100',
    'amber' => 'from-amber-500 to-amber-600 text-amber-100',
    'red' => 'from-red-500 to-red-600 text-red-100',
    'indigo' => 'from-indigo-500 to-indigo-600 text-indigo-100',
    'pink' => 'from-pink-500 to-pink-600 text-pink-100',
    'teal' => 'from-teal-500 to-teal-600 text-teal-100',
];

$gradientClass = $gradients[$gradient] ?? $gradients['blue'];
@endphp

<div class="relative overflow-hidden bg-gradient-to-br {{ $gradientClass }} rounded-xl shadow-lg p-6 text-white">
    {{-- Decorative Circle --}}
    <div class="absolute top-0 right-0 -mt-4 -mr-4 h-24 w-24 rounded-full bg-white/10"></div>

    <div class="relative">
        {{-- Icon --}}
        @if($icon || $slot->isNotEmpty())
        <div class="flex items-center justify-between mb-4">
            <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm">
                <div class="[&>svg]:w-6 [&>svg]:h-6">
                    @if($icon)
                        {!! $icon !!}
                    @else
                        {{ $slot }}
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- Content --}}
        <div>
            <p class="text-sm font-medium mb-1 opacity-90">{{ $label }}</p>
            <p class="text-3xl font-bold">{{ $value }}</p>
            @if($subtitle)
            <p class="text-sm mt-2 opacity-90">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
</div>
