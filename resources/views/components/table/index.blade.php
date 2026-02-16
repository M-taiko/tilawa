@props([
 'class' => '',
])

<div {{ $attributes->merge(['class' => 'overflow-x-auto border border-gray-200 rounded-lg bg-white ' . $class]) }}>
 <table class="w-full text-sm">
 {{ $slot }}
 </table>
</div>
