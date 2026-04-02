@extends('layouts.app')

@section('title', $juz->name_arabic . ' — الجزء ' . $juz->id . ' من القرآن الكريم | تلاوة')
@section('meta_description', 'قراءة ' . $juz->name_arabic . ' (الجزء ' . $juz->id . ') من القرآن الكريم بالرسم العثماني — تطبيق تلاوة.')
@section('canonical', url('/quran/juz/' . $juz->id))

@section('content')
<div class="min-h-screen p-4 md:p-6 pattern-subtle">
    {{-- Header --}}
    <div class="mb-6 animate-fadeInUp">
        <div class="max-w-6xl mx-auto">
            <div class="flex items-center justify-between gap-4 mb-4">
                <x-button variant="ghost" href="{{ route('quran.index') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                    </svg>
                    رجوع للفهرس
                </x-button>

                <div class="flex gap-2">
                    @if($juzNumber > 1)
                        <a href="{{ route('quran.juz', $juzNumber - 1) }}"
                           class="p-2 rounded-lg bg-white border-2 border-gold-200 text-gold-700 hover:bg-gold-50 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @endif

                    @if($juzNumber < 30)
                        <a href="{{ route('quran.juz', $juzNumber + 1) }}"
                           class="p-2 rounded-lg bg-white border-2 border-gold-200 text-gold-700 hover:bg-gold-50 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Juz Info Card --}}
            <x-card islamic class="p-6 text-center bg-gradient-to-r from-gold-50 to-emerald-50">
                <h1 class="text-3xl md:text-4xl font-bold text-gold-800 mb-3">{{ $juz->name_arabic }}</h1>
                <div class="flex justify-center gap-4 text-sm text-slate-600">
                    <span class="px-3 py-1 rounded-full bg-white border border-gold-200">
                        من {{ $juz->startSurah->name_arabic }} ({{ $juz->start_verse_number }})
                    </span>
                    <span class="px-3 py-1 rounded-full bg-white border border-gold-200">
                        إلى {{ $juz->endSurah->name_arabic }} ({{ $juz->end_verse_number }})
                    </span>
                </div>
            </x-card>
        </div>
    </div>

    {{-- Juz Content --}}
    <div class="max-w-6xl mx-auto animate-fadeInUp stagger-1">
        <x-card islamic class="p-6 md:p-8 bg-gradient-to-br from-amber-50 via-white to-gold-50">
            <div class="quran-content">
                @php
                    $currentSurah = null;
                @endphp

                @foreach($verses as $verse)
                    {{-- عنوان السورة إذا تغيرت --}}
                    @if($currentSurah != $verse->surah_id)
                        @php $currentSurah = $verse->surah_id; @endphp
                        <div class="surah-header text-center my-6 animate-fadeIn">
                            <div class="inline-block px-6 py-3 rounded-xl bg-gradient-to-r from-gold-600 to-gold-700 text-white shadow-lg">
                                <h2 class="text-xl md:text-2xl font-bold">سورة {{ $verse->surah->name_arabic }}</h2>
                            </div>
                        </div>

                        {{-- البسملة (ما عدا سورة التوبة وإذا كانت بداية السورة) --}}
                        @if($verse->verse_number == 1 && $verse->surah_id != 9)
                            <div class="text-center mb-6">
                                <p class="quran-font text-2xl md:text-3xl text-gold-800">بِسْمِ ٱللَّهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ</p>
                            </div>
                        @endif
                    @endif

                    {{-- نص الآية --}}
                    <div class="inline">
                        <span class="quran-font text-xl md:text-2xl leading-loose text-slate-900">
                            {{ $verse->verse_text }}
                        </span>
                        <span class="verse-number inline-flex items-center justify-center w-6 h-6 mx-1 text-xs font-bold bg-gold-600 text-white rounded-full shadow-sm">
                            {{ $verse->verse_number }}
                        </span>
                    </div>
                @endforeach
            </div>
        </x-card>
    </div>

    {{-- Navigation --}}
    <div class="max-w-6xl mx-auto mt-6 animate-fadeInUp stagger-2">
        <div class="flex justify-between items-center">
            @if($juz->previous())
                <x-button variant="gold" href="{{ route('quran.juz', $juz->previous()->id) }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    {{ $juz->previous()->name_arabic }}
                </x-button>
            @else
                <div></div>
            @endif

            <a href="{{ route('quran.index') }}" class="text-gold-700 hover:text-gold-900 font-semibold">
                العودة للفهرس
            </a>

            @if($juz->next())
                <x-button variant="gold" href="{{ route('quran.juz', $juz->next()->id) }}">
                    {{ $juz->next()->name_arabic }}
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </x-button>
            @else
                <div></div>
            @endif
        </div>
    </div>
</div>

<style>
.quran-font {
    font-family: 'Amiri Quran', 'Scheherazade New', 'Traditional Arabic', serif;
    line-height: 2.3;
}

.quran-content {
    text-align: justify;
    direction: rtl;
    min-height: 600px;
}

.verse-number {
    vertical-align: middle;
}

.surah-header {
    margin: 2rem 0;
}
</style>
@endsection
