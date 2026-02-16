@props([
 'class' => '',
])

<tr {{ $attributes->merge(['class' => 'hover:bg-gray-50 transition-colors duration-150 ' . $class]) }}>
 {{ $slot }}
</tr>
