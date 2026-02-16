@props([
 'variant' => 'info',
 'title' => null,
 'dismissible' => false,
 'icon' => null,
 'class' => '',
])

@php
 $baseClasses = 'px-5 py-4 rounded-lg border flex items-start gap-3 animate-slide-in-top transition-colors';

 $variantClasses = [
 'success' => 'bg-green-50 border-green-200 text-green-900',

 'error' => 'bg-red-50 border-red-200 text-red-900',

 'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-900',

 'info' => 'bg-cyan-50 border-cyan-200 text-cyan-900',
 ];

 $iconColorClasses = [
 'success' => 'text-green-600',
 'error' => 'text-red-600',
 'warning' => 'text-yellow-600',
 'info' => 'text-cyan-600',
 ];

 $defaultIcons = [
 'success' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
 'error' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
 'warning' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
 'info' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
 ];

 $classes = collect([
 $baseClasses,
 $variantClasses[$variant] ?? $variantClasses['info'],
 $class,
 ])->implode(' ');
@endphp

<div {{ $attributes->merge(['class' => $classes]) }} role="alert">
 {{-- Icon --}}
 @if ($icon)
 <div class="flex-shrink-0 {{ $iconColorClasses[$variant] ?? $iconColorClasses['info'] }}">
 {{ $icon }}
 </div>
 @else
 <div class="flex-shrink-0 {{ $iconColorClasses[$variant] ?? $iconColorClasses['info'] }}">
 {!! $defaultIcons[$variant] ?? '' !!}
 </div>
 @endif

 {{-- Content --}}
 <div class="flex-1 min-w-0">
 @if ($title)
 <div class="font-semibold text-sm mb-1">{{ $title }}</div>
 @endif
 <div class="text-sm {{ $title ? '' : 'leading-relaxed' }}">
 {{ $slot }}
 </div>
 </div>

 {{-- Dismiss Button --}}
 @if ($dismissible)
 <button
 type="button"
 class="flex-shrink-0 p-1 rounded-lg transition-colors {{ $iconColorClasses[$variant] ?? $iconColorClasses['info'] }} hover:bg-black/5 focus:outline-none focus-visible:ring-2 focus-visible:ring-current"
 onclick="this.closest('[role=alert]').remove()"
 aria-label="إغلاق"
 >
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
 </svg>
 </button>
 @endif
</div>
