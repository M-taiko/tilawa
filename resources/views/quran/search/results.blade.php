@extends('layouts.app')

@section('title', 'نتائج البحث - المصحف الكريم')

@section('content')
<div class="min-h-screen p-4 md:p-6 pattern-subtle">
    {{-- Header --}}
    <div class="mb-6 animate-fadeInUp">
        <div class="max-w-5xl mx-auto">
            <div class="flex items-center justify-between gap-4 mb-4">
                <x-button variant="ghost" href="{{ route('quran.search.index') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                    </svg>
                    {{ app()->getLocale() === 'ar' ? 'بحث جديد' : 'New Search' }}
                </x-button>

                {{-- Language Toggle --}}
                <div class="flex items-center gap-1 bg-slate-100 rounded-lg p-1">
                    <form method="POST" action="{{ route('locale.switch') }}" class="inline">
                        @csrf
                        <input type="hidden" name="locale" value="ar">
                        <button type="submit" class="px-2.5 py-1.5 rounded-md text-xs font-semibold transition-colors {{ app()->getLocale() === 'ar' ? 'bg-white text-primary-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}" style="font-family:'Tajawal',sans-serif;">
                            العربية
                        </button>
                    </form>
                    <form method="POST" action="{{ route('locale.switch') }}" class="inline">
                        @csrf
                        <input type="hidden" name="locale" value="en">
                        <button type="submit" class="px-2.5 py-1.5 rounded-md text-xs font-semibold transition-colors {{ app()->getLocale() === 'en' ? 'bg-white text-primary-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}" style="font-family:'Tajawal',sans-serif;">
                            EN
                        </button>
                    </form>
                </div>
            </div>

            <x-card islamic class="p-6 bg-gradient-to-r from-accent-50 to-emerald-50">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-accent-800 mb-2">{{ app()->getLocale() === 'ar' ? 'نتائج البحث' : 'Search Results' }}</h1>
                        <p class="text-slate-600">{{ app()->getLocale() === 'ar' ? 'البحث عن:' : 'Searching for:' }} <span class="font-bold text-accent-700">"{{ $query }}"</span></p>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-accent-700">{{ $results->total() }}</div>
                        <div class="text-sm text-slate-600">{{ app()->getLocale() === 'ar' ? 'نتيجة' : 'result' . ($results->total() !== 1 ? 's' : '') }}</div>
                    </div>
                </div>

                {{-- Active Filters --}}
                @if(array_filter($filters))
                    <div class="mt-4 pt-4 border-t border-accent-100">
                        <p class="text-sm text-slate-600 mb-2">{{ app()->getLocale() === 'ar' ? 'الفلاتر المطبقة:' : 'Applied Filters:' }}</p>
                        <div class="flex flex-wrap gap-2">
                            @if(isset($filters['surah_id']) && $filters['surah_id'])
                                @php
                                    $surah = $surahs->find($filters['surah_id']);
                                @endphp
                                <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 text-sm font-semibold">
                                    {{ app()->getLocale() === 'ar' ? $surah?->name_arabic : $surah?->name_english }}
                                </span>
                            @endif
                            @if(isset($filters['juz_number']) && $filters['juz_number'])
                                <span class="px-3 py-1 rounded-full bg-gold-100 text-gold-700 text-sm font-semibold">
                                    {{ app()->getLocale() === 'ar' ? 'الجزء ' . $filters['juz_number'] : 'Juz ' . $filters['juz_number'] }}
                                </span>
                            @endif
                            @if(isset($filters['page_number']) && $filters['page_number'])
                                <span class="px-3 py-1 rounded-full bg-accent-100 text-accent-700 text-sm font-semibold">
                                    {{ app()->getLocale() === 'ar' ? 'صفحة ' . $filters['page_number'] : 'Page ' . $filters['page_number'] }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            </x-card>
        </div>
    </div>

    {{-- Results --}}
    <div class="max-w-5xl mx-auto space-y-4 animate-fadeInUp stagger-1">
        @if($results->isEmpty())
            <x-card variant="warning" class="p-8 text-center">
                <svg class="w-16 h-16 mx-auto text-warning-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-xl font-bold text-warning-800 mb-2">{{ app()->getLocale() === 'ar' ? 'لا توجد نتائج' : 'No Results' }}</h3>
                <p class="text-warning-700 mb-4">{{ app()->getLocale() === 'ar' ? 'لم يتم العثور على نتائج للبحث' : 'No results found for' }} "{{ $query }}"</p>
                <x-button variant="gold" href="{{ route('quran.search.index') }}">
                    {{ app()->getLocale() === 'ar' ? 'بحث جديد' : 'New Search' }}
                </x-button>
            </x-card>
        @else
            @foreach($results as $verse)
                <x-card islamic class="p-6 hover:shadow-lg transition-all duration-300">
                    <div class="flex flex-col md:flex-row gap-4">
                        {{-- Verse Info --}}
                        <div class="flex-shrink-0 md:w-48">
                            <a href="{{ route('quran.surah', $verse->surah_id) }}"
                               class="block p-4 rounded-xl bg-gradient-to-br from-emerald-50 to-gold-50 border-2 border-emerald-200 hover:border-gold-400 transition-colors">
                                <h3 class="font-bold text-emerald-800 mb-1">{{ app()->getLocale() === 'ar' ? $verse->surah->name_arabic : $verse->surah->name_english }}</h3>
                                <div class="text-sm text-slate-600 space-y-1">
                                    <div>{{ app()->getLocale() === 'ar' ? 'الآية:' : 'Verse:' }} {{ $verse->verse_number }}</div>
                                    <div>{{ app()->getLocale() === 'ar' ? 'الصفحة:' : 'Page:' }} {{ $verse->page_number }}</div>
                                    <div>{{ app()->getLocale() === 'ar' ? 'الجزء:' : 'Juz:' }} {{ $verse->juz_number }}</div>
                                </div>
                            </a>
                        </div>

                        {{-- Verse Text --}}
                        <div class="flex-1">
                            <div class="quran-font text-xl md:text-2xl leading-relaxed text-slate-900 p-4 bg-gradient-to-br from-amber-50 to-white rounded-xl">
                                {!! str_replace(
                                    $query,
                                    '<mark class="bg-gold-200 text-gold-900 font-semibold px-1 rounded">' . $query . '</mark>',
                                    $verse->verse_text
                                ) !!}
                            </div>

                            {{-- English Translation --}}
                            @if(app()->getLocale() === 'en' && $verse->verse_text_english)
                            <div style="direction: ltr; text-align: left; font-family: 'Segoe UI', sans-serif; font-size: 0.95rem; color: #666; line-height: 1.8; margin: 12px 0 0 0; font-style: italic; padding: 12px; background: rgba(255,255,255,0.5); border-radius: 8px;">
                                {{ $verse->verse_text_english }}
                            </div>
                            @endif

                            {{-- Actions --}}
                            <div class="flex gap-2 mt-3">
                                <a href="{{ route('quran.page', $verse->page_number) }}"
                                   class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald-100 text-emerald-700 hover:bg-emerald-200 transition-colors text-sm font-semibold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    {{ app()->getLocale() === 'ar' ? 'عرض في المصحف' : 'View in Mushaf' }}
                                </a>
                                <a href="{{ route('quran.surah', $verse->surah_id) }}"
                                   class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gold-100 text-gold-700 hover:bg-gold-200 transition-colors text-sm font-semibold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    {{ app()->getLocale() === 'ar' ? 'عرض السورة' : 'View Surah' }}
                                </a>
                            </div>
                        </div>
                    </div>
                </x-card>
            @endforeach

            {{-- Pagination --}}
            @if($results->hasPages())
                <div class="mt-6">
                    {{ $results->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

<style>
.quran-font {
    font-family: 'Amiri Quran', 'Scheherazade New', 'Traditional Arabic', serif;
    direction: rtl;
}

mark {
    animation: highlight 0.5s ease-in-out;
}

@keyframes highlight {
    0% { background-color: transparent; }
    50% { background-color: #fbbf24; }
    100% { background-color: #fde68a; }
}
</style>
@endsection
