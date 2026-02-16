@props([
 'name' => null,
 'image' => null,
 'size' => 'md',
 'class' => '',
])

@php
 $baseClasses = 'inline-flex items-center justify-center rounded-full bg-blue-600 text-white font-semibold overflow-hidden ring-2 ring-white shadow-sm transition-colors';

 $sizeClasses = [
 'xs' => 'h-6 w-6 text-xs',
 'sm' => 'h-8 w-8 text-sm',
 'md' => 'h-10 w-10 text-base',
 'lg' => 'h-14 w-14 text-xl',
 'xl' => 'h-20 w-20 text-3xl',
 '2xl' => 'h-28 w-28 text-4xl',
 ];

 $initials = '';
 if (!$image && $name) {
 $words = preg_split('/\s+/', trim($name));
 if (count($words) >= 2) {
 $initials = mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1);
 } else {
 $initials = mb_substr($name, 0, 2);
 }
 }

 $classes = collect([
 $baseClasses,
 $sizeClasses[$size] ?? $sizeClasses['md'],
 $class,
 ])->implode(' ');
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
 @if ($image)
 <img
 src="{{ $image }}"
 alt="{{ $name ?? 'Avatar' }}"
 class="w-full h-full object-cover"
 >
 @elseif ($initials)
 <span class="uppercase">{{ $initials }}</span>
 @else
 <svg class="w-3/5 h-3/5 opacity-80" fill="currentColor" viewBox="0 0 24 24">
 <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
 </svg>
 @endif
</div>
