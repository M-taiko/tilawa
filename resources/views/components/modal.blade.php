@props([
    'id' => 'modal',
    'title' => '',
    'subtitle' => null,
    'icon' => null,
    'iconColor' => 'blue',
    'size' => 'lg', // sm, md, lg, xl
    'closeFunction' => null,
])

@php
$sizes = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
];

$iconColors = [
    'blue' => 'bg-blue-100 text-blue-600',
    'green' => 'bg-green-100 text-green-600',
    'red' => 'bg-red-100 text-red-600',
    'yellow' => 'bg-yellow-100 text-yellow-600',
    'purple' => 'bg-purple-100 text-purple-600',
    'gray' => 'bg-gray-100 text-gray-600',
];

$sizeClass = $sizes[$size] ?? $sizes['lg'];
$iconColorClass = $iconColors[$iconColor] ?? $iconColors['blue'];
$closeFn = $closeFunction ?? "document.getElementById('{$id}').classList.add('hidden')";
@endphp

<div id="{{ $id }}" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="{{ $id }}-title" role="dialog" aria-modal="true">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" onclick="{{ $closeFn }}"></div>

    {{-- Modal Container --}}
    <div class="flex min-h-full items-center justify-center p-4">
        {{-- Modal Content --}}
        <div class="relative transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all w-full {{ $sizeClass }}" onclick="event.stopPropagation()">
            {{-- Header --}}
            <div class="bg-white px-6 pt-6 pb-4">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        @if($icon || isset($iconSlot))
                        <div class="flex h-12 w-12 items-center justify-center rounded-full {{ $iconColorClass }}">
                            <div class="[&>svg]:w-6 [&>svg]:h-6">
                                @if($icon)
                                    {!! $icon !!}
                                @elseif(isset($iconSlot))
                                    {{ $iconSlot }}
                                @endif
                            </div>
                        </div>
                        @endif
                        <div>
                            <h3 class="text-xl font-bold text-gray-900" id="{{ $id }}-title">{{ $title }}</h3>
                            @if($subtitle)
                            <p class="text-sm text-gray-500 mt-1">{{ $subtitle }}</p>
                            @endif
                        </div>
                    </div>
                    <button type="button" onclick="{{ $closeFn }}" class="rounded-lg p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                        <span class="sr-only">إغلاق</span>
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Body --}}
            <div class="bg-white px-6 py-4">
                {{ $slot }}
            </div>

            {{-- Footer --}}
            @if(isset($footer))
            <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3">
                {{ $footer }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
// Auto-close when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('{{ $id }}');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                {{ $closeFn }};
            }
        });
    }
});
</script>
