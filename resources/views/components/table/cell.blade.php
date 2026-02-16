@props([
 'class' => '',
])

<td {{ $attributes->merge(['class' => 'px-6 py-4 text-gray-700 whitespace-nowrap ' . $class]) }}>
 {{ $slot }}
</td>
