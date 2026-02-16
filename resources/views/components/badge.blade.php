@props([
 'variant' => 'neutral',
 'class' => '',
 'icon' => null,
])

@php
 $baseClasses = 'inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold rounded-full transition-colors';

 $variantClasses = [
 'primary' => 'bg-blue-100 text-blue-700 border border-blue-200',

 'secondary' => 'bg-gray-100 text-gray-700 border border-gray-200',

 'success' => 'bg-green-100 text-green-700 border border-green-200',

 'warning' => 'bg-yellow-100 text-yellow-700 border border-yellow-200',

 'error' => 'bg-red-100 text-red-700 border border-red-200',

 'info' => 'bg-cyan-100 text-cyan-700 border border-cyan-200',

 'neutral' => 'bg-gray-100 text-gray-700 border border-gray-200',
 ];

 $classes = collect([
 $baseClasses,
 $variantClasses[$variant] ?? $variantClasses['neutral'],
 $class,
 ])->implode(' ');
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
 @if($icon)
 {{ $icon }}
 @endif
 {{ $slot }}
</span>
