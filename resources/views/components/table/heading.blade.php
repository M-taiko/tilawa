@props([
 'class' => '',
 'sortable' => null,
 'direction' => 'asc',
])

<th {{ $attributes->merge(['class' => 'px-6 py-3 text-right font-semibold text-gray-700 text-xs uppercase tracking-wide ' . $class]) }}>
 @if($sortable)
 <button class="flex items-center gap-1 group">
 {{ $slot }}
 <svg class="w-3 h-3 text-gray-400 group-hover:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
 </svg>
 </button>
 @else
 {{ $slot }}
 @endif
</th>
