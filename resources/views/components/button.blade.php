@props([
 'variant' => 'primary',
 'size' => 'md',
 'type' => 'button',
 'loading' => false,
 'disabled' => false,
 'icon' => null,
 'href' => null,
 'class' => '',
])

@php
 // Modern base styles
 $baseClasses = 'inline-flex items-center justify-center gap-2 font-semibold transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-offset-2 disabled:opacity-60 disabled:cursor-not-allowed active:scale-[0.98] select-none cursor-pointer';

 // Modern variant styles
 $variantClasses = [
 'primary' => 'bg-gradient-to-br from-primary-600 to-primary-700 text-white hover:to-primary-800 shadow-md shadow-primary-500/20 focus:ring-primary-500/30 border border-transparent',
 'secondary' => 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50 hover:border-slate-300 shadow-sm focus:ring-slate-200',
 'ghost' => 'bg-transparent text-slate-600 hover:bg-slate-100/50 hover:text-slate-900 focus:ring-slate-100',
 'outline' => 'bg-transparent border-2 border-primary-500 text-primary-600 hover:bg-primary-50 focus:ring-primary-100',
 'danger' => 'bg-gradient-to-br from-error-500 to-error-600 text-white hover:to-error-700 shadow-md shadow-error-500/20 focus:ring-error-500/30 border border-transparent',
 'success' => 'bg-gradient-to-br from-success-500 to-success-600 text-white hover:to-success-700 shadow-md shadow-success-500/20 focus:ring-success-500/30 border border-transparent',
 'warning' => 'bg-gradient-to-br from-warning-500 to-warning-600 text-white hover:to-warning-700 shadow-md shadow-warning-500/20 focus:ring-warning-500/30 border border-transparent',
 'glass' => 'bg-white/20 backdrop-blur-md border border-white/30 text-white hover:bg-white/30 shadow-sm',
 ];

 // Modern size styles
 $sizeClasses = [
 'xs' => 'px-3 py-1.5 text-xs rounded-lg',
 'sm' => 'px-3.5 py-2 text-sm rounded-lg',
 'md' => 'px-5 py-2.5 text-sm rounded-xl',
 'lg' => 'px-6 py-3 text-base rounded-xl',
 'xl' => 'px-7 py-3.5 text-lg rounded-2xl',
 ];

 // Combine classes
 $classes = collect([
 $baseClasses,
 $variantClasses[$variant] ?? $variantClasses['primary'],
 $sizeClasses[$size] ?? $sizeClasses['md'],
 $class,
 ])->implode(' ');

 $finalDisabled = $disabled || $loading;
@endphp

@if ($href)
 <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }} @if($finalDisabled) tabindex="-1" aria-disabled="true" @endif>
 @if($loading)
 <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
 <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
 </svg>
 @elseif($icon)
 {{ $icon }}
 @endif
 {{ $slot }}
 </a>
@else
 <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }} @if($finalDisabled) disabled @endif>
 @if($loading)
 <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
 <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
 </svg>
 @elseif($icon)
 {{ $icon }}
 @endif
 {{ $slot }}
 </button>
@endif
