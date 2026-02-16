@props([
 'class' => '',
])

<tbody {{ $attributes->merge(['class' => 'divide-y divide-gray-200 bg-white ' . $class]) }}>
 {{ $slot }}
</tbody>
