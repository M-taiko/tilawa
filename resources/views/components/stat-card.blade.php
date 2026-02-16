@props([
 'label',
 'value',
 'color' => 'primary',
 'trend' => null,
 'trendPositive' => true,
])

@php
 $colors = [
 'primary' => [
 'border' => 'border-primary-500', 
 'icon-bg' => 'bg-primary-50', 
 'icon-text' => 'text-primary-600',
 'trend-text' => 'text-primary-600'
 ],
 'success' => [
 'border' => 'border-green-500', 
 'icon-bg' => 'bg-green-100', 
 'icon-text' => 'text-green-600',
 'trend-text' => 'text-green-600'
 ],
 'warning' => [
 'border' => 'border-amber-500', 
 'icon-bg' => 'bg-amber-100', 
 'icon-text' => 'text-amber-600',
 'trend-text' => 'text-amber-600'
 ],
 'info' => [
 'border' => 'border-sky-500', 
 'icon-bg' => 'bg-sky-100', 
 'icon-text' => 'text-sky-600',
 'trend-text' => 'text-sky-600'
 ],
 'error' => [
 'border' => 'border-red-500', 
 'icon-bg' => 'bg-red-100', 
 'icon-text' => 'text-red-600',
 'trend-text' => 'text-red-600'
 ],
 ];

 $style = $colors[$color] ?? $colors['primary'];
@endphp

<div class="bg-white rounded-xl shadow-sm p-6 border-r-4 {{ $style['border'] }} hover:shadow-md transition-shadow duration-200">
 <div class="flex items-center justify-between">
 <div>
 <p class="text-sm text-gray-500 font-medium mb-1">{{ $label }}</p>
 <h3 class="text-3xl font-bold text-gray-900">{{ $value }}</h3>
 
 @if($trend)
 <div class="flex items-center gap-1 mt-2 text-xs font-semibold {{ $trendPositive ? 'text-emerald-600' : 'text-rose-600' }}">
 <span>{{ $trend }}</span>
 <svg class="w-3 h-3 {{ $trendPositive ? '' : 'rotate-180' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
 </svg>
 <span class="text-gray-400 font-normal mr-1">الشهر الماضي</span>
 </div>
 @endif
 </div>

 <div class="p-3 {{ $style['icon-bg'] }} rounded-full flex items-center justify-center shrink-0">
 <div class="{{ $style['icon-text'] }} [&>svg]:w-8 [&>svg]:h-8">
 {{ $slot }}
 </div>
 </div>
 </div>
</div>
