@props([
    'title',
    'subtitle' => null,
    'action' => null,
    'height' => '320px',
])

<div class="bg-white rounded-xl border border-gray-200 shadow-sm">
    {{-- Header --}}
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-gray-900">{{ $title }}</h3>
                @if($subtitle)
                <p class="text-sm text-gray-500 mt-1">{{ $subtitle }}</p>
                @endif
            </div>
            @if($action || isset($actionSlot))
            <div>
                @if($action)
                    {!! $action !!}
                @else
                    {{ $actionSlot }}
                @endif
            </div>
            @endif
        </div>
    </div>

    {{-- Body --}}
    <div class="p-6">
        <div style="height: {{ $height }}">
            {{ $slot }}
        </div>
    </div>
</div>
