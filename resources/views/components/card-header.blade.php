@props([
 'class' => '',
])

<div {{ $attributes->merge(['class' => 'px-6 py-5 border-b border-gray-100 ' . $class]) }}>
 {{ $slot }}
</div>
