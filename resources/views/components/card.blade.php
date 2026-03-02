@props([
 'class' => '',
 'padding' => 'p-0',
 'variant' => null,
 'islamic' => false
])

@php
 // Islamic Design - تصميم إسلامي
 if ($islamic) {
  $baseClasses = 'bg-white/95 rounded-2xl shadow-lg border border-primary-100/50 hover:shadow-xl transition-all duration-300 relative overflow-hidden';
  $borderClass = '';
 } else {
  $borderClass = match($variant) {
   'primary', 'emerald' => 'border-r-4 border-r-primary-500',
   'gold' => 'border-r-4 border-r-gold-500',
   'success', 'green' => 'border-r-4 border-r-success-500',
   'warning', 'amber' => 'border-r-4 border-r-warning-500',
   'danger', 'error', 'red' => 'border-r-4 border-r-error-500',
   'info', 'sky' => 'border-r-4 border-r-accent-500',
   default => ''
  };
  $baseClasses = 'bg-white rounded-xl shadow-sm border border-primary-50 hover:shadow-md transition-all duration-200';
 }
@endphp

<div {{ $attributes->merge(['class' => "$baseClasses $borderClass $class"]) }}>
 @if($islamic)
  <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-primary-500 via-gold-500 to-primary-500"></div>
 @endif
 {{ $slot }}
</div>
