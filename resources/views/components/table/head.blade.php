@props([
 'class' => '',
])

<thead {{ $attributes->merge(['class' => 'bg-gray-50 border-b border-gray-200 ' . $class]) }}>
 <tr>
 {{ $slot }}
 </tr>
</thead>
