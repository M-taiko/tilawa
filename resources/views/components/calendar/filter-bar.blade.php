@props([
    'groups' => [],
    'tracks' => [],
])

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
        {{-- Search Input --}}
        <div class="md:col-span-2">
            <x-input
                name="search"
                value="{{ request('search') }}"
                placeholder="ابحث عن حلقة..."
                class="w-full"
            >
                <x-slot name="icon">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </x-slot>
            </x-input>
        </div>

        {{-- Group Filter --}}
        <x-select
            name="group"
            :options="$groups"
            :selected="request('group')"
            placeholder="كل المجموعات"
        />

        {{-- Track Filter --}}
        <x-select
            name="track"
            :options="$tracks"
            :selected="request('track')"
            placeholder="كل المسارات"
        />

        {{-- Action Buttons --}}
        <div class="md:col-span-4 flex gap-2">
            <x-button type="submit" variant="primary" size="sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                بحث
            </x-button>

            @if(request()->hasAny(['search', 'group', 'track']))
                <x-button href="{{ route('admin.schedules.calendar') }}" variant="outline" size="sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    إعادة تعيين
                </x-button>
            @endif
        </div>
    </form>
</div>
