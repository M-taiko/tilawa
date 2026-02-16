@props([
    'label',
    'value',
    'icon' => null,
])

<div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-sm transition-shadow">
    <div class="flex items-center gap-3">
        @if($icon || $slot->isNotEmpty())
        <div class="p-2 bg-gray-100 rounded-lg flex-shrink-0">
            <div class="[&>svg]:w-5 [&>svg]:h-5 text-gray-600">
                @if($icon)
                    {!! $icon !!}
                @else
                    {{ $slot }}
                @endif
            </div>
        </div>
        @endif
        <div>
            <p class="text-2xl font-bold text-gray-900">{{ $value }}</p>
            <p class="text-xs text-gray-500">{{ $label }}</p>
        </div>
    </div>
</div>
