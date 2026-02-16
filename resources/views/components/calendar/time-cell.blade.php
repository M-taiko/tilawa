@props([
    'time',
    'label',
])

<div class="sticky left-0 z-10 px-3 py-3 text-center bg-gradient-to-l from-gray-50 to-gray-100 border-r-2 border-gray-300">
    <div class="text-sm font-semibold text-gray-700">{{ $label }}</div>
    <div class="text-xs text-gray-500 mt-0.5">{{ $time }}</div>
</div>
