@props([
 'class' => '',
])

<div {{ $attributes->merge(['class' => 'px-6 py-4 border-t border-gray-100 bg-gray-50/50 ' . $class]) }}>
 {{ $slot }}
</div>
