@props([
 'class' => '', 
 'padding' => 'p-0',
 'variant' => null
])

@php
 $borderClass = match($variant) {
 'primary', 'blue' => 'border-r-4 border-r-blue-500',
 'success', 'green' => 'border-r-4 border-r-green-500',
 'warning', 'amber' => 'border-r-4 border-r-amber-500',
 'danger', 'error', 'red' => 'border-r-4 border-r-red-500',
 'info', 'sky' => 'border-r-4 border-r-sky-500',
 'purple' => 'border-r-4 border-r-purple-500',
 default => ''
 };
@endphp

<div {{ $attributes->merge(['class' => "bg-white rounded-lg shadow-sm border border-gray-100 $borderClass $class"]) }}>
 {{ $slot }}
</div>
