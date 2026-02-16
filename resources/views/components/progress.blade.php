@props([
 'value' => 0,
 'max' => 100,
 'variant' => 'primary',
 'size' => 'md',
 'animated' => false,
 'striped' => false,
 'showLabel' => false,
 'class' => '',
])

@php
 $percentage = ($max > 0) ? min(($value / $max) * 100, 100) : 0;

 $containerSizeClasses = [
 'sm' => 'h-1.5',
 'md' => 'h-2.5',
 'lg' => 'h-4',
 ];

 $variantClasses = [
 'primary' => 'bg-blue-600',
 'success' => 'bg-green-600',
 'warning' => 'bg-yellow-600',
 'error' => 'bg-red-600',
 'info' => 'bg-cyan-600',
 ];

 $barClasses = collect([
 'h-full rounded-full transition-all duration-500 ease-out',
 $variantClasses[$variant] ?? $variantClasses['primary'],
 ]);

 if ($animated) {
 $barClasses->push('animate-pulse');
 }

 if ($striped) {
 $barClasses->push('bg-gradient-striped');
 }
@endphp

<div class="w-full {{ $class }}">
 @if ($showLabel)
 <div class="flex items-center justify-between mb-2">
 <span class="text-sm font-medium text-gray-700">{{ $slot }}</span>
 <span class="text-sm font-semibold text-gray-900">{{ round($percentage) }}%</span>
 </div>
 @endif

 <div
 class="w-full {{ $containerSizeClasses[$size] ?? $containerSizeClasses['md'] }} bg-gray-200 rounded-full overflow-hidden"
 role="progressbar"
 aria-valuenow="{{ $value }}"
 aria-valuemin="0"
 aria-valuemax="{{ $max }}"
 aria-label="{{ $slot->isNotEmpty() ? $slot : 'Progress' }}"
 >
 <div
 class="{{ $barClasses->implode(' ') }}"
 style="width: {{ $percentage }}%"
 ></div>
 </div>
</div>

@if ($striped)
 <style>
 .bg-gradient-striped {
 background-image: linear-gradient(
 45deg,
 rgba(255, 255, 255, 0.15) 25%,
 transparent 25%,
 transparent 50%,
 rgba(255, 255, 255, 0.15) 50%,
 rgba(255, 255, 255, 0.15) 75%,
 transparent 75%,
 transparent
 );
 background-size: 1rem 1rem;
 }
 </style>
@endif
