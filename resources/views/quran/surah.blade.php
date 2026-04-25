@extends('layouts.app')

@section('title', 'سورة ' . $surah->name_arabic . ' (' . $surah->ayah_count . ' آية) | تلاوة')
@section('meta_description', 'قراءة سورة ' . $surah->name_arabic . ' كاملةً — ' . $surah->ayah_count . ' آية — من القرآن الكريم بالرسم العثماني في تطبيق تلاوة.')
@section('canonical', url('/quran/surah/' . $surah->id))

@push('styles')
<style>
.quran-content {
    text-align: justify;
    text-align-last: center;
    direction: rtl;
    word-spacing: 0.2em;
    letter-spacing: 0.08em;
}
.quran-font {
    font-family: 'KFGQPC Uthmanic', 'Amiri Quran', 'Scheherazade New', serif !important;
    font-size: 1.95rem !important;
    line-height: 3.4 !important;
    color: #000000 !important;
    font-weight: 700 !important;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-rendering: geometricPrecision;
}
.verse-number {
    background: radial-gradient(circle at 30% 30%, #f9f5ed 0%, #f0e6d8 50%, #dcc9a8 100%) !important;
    border-color: #8b6f47 !important;
    border-width: 2.5px !important;
    color: #4a3920 !important;
    font-size: 0.7rem !important;
    box-shadow: 0 3px 8px rgba(0,0,0,0.15), inset 0 1px 2px rgba(255,255,255,0.8) !important;
}
.verse-translation {
    font-style: italic;
    color: #555;
    font-size: 0.95rem;
    line-height: 1.9;
    margin: 10px 0 0 0;
    padding: 10px 14px;
    background: rgba(200,200,200,0.05);
    border-right: 3px solid #8b6f47;
    border-radius: 3px;
}
</style>
@endpush

@section('content')
<div class="min-h-screen p-4 md:p-6 pattern-subtle">
    {{-- Header --}}
    <div class="mb-6 animate-fadeInUp">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center justify-between gap-4 mb-4">
                <x-button variant="ghost" href="{{ route('quran.index') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                    </svg>
                    {{ app()->getLocale() === 'ar' ? 'رجوع للفهرس' : 'Back to Index' }}
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

                @if($surah->start_page)
                    <x-button variant="primary" href="{{ route('quran.page', $surah->start_page) }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        {{ app()->getLocale() === 'ar' ? 'عرض في المصحف' : 'View in Mushaf' }}
                    </x-button>
                @endif
            </div>

            {{-- Surah Info Card --}}
            <x-card islamic class="p-6 text-center bg-gradient-to-r from-emerald-50 to-gold-50">
                <h1 class="text-3xl md:text-4xl font-bold text-emerald-800 mb-3">{{ app()->getLocale() === 'ar' ? 'سورة ' . $surah->name_arabic : 'Surah ' . $surah->name_english }}</h1>
                <div class="flex justify-center gap-4 text-sm text-slate-600">
                    <span class="px-3 py-1 rounded-full bg-white border border-emerald-200">
                        {{ app()->getLocale() === 'ar' ? $surah->ayah_count . ' آية' : $surah->ayah_count . ' verses' }}
                    </span>
                    @if($surah->start_page && $surah->end_page)
                        <span class="px-3 py-1 rounded-full bg-white border border-emerald-200">
                            {{ app()->getLocale() === 'ar' ? 'من صفحة ' . $surah->start_page . ' إلى ' . $surah->end_page : 'Pages ' . $surah->start_page . '-' . $surah->end_page }}
                        </span>
                    @endif
                    @if($surah->juz)
                        <span class="px-3 py-1 rounded-full bg-white border border-emerald-200">
                            {{ app()->getLocale() === 'ar' ? $surah->juz->name_arabic : 'Juz ' . $surah->juz->id }}
                        </span>
                    @endif
                </div>
            </x-card>
        </div>
    </div>

    {{-- Surah Content --}}
    <div class="max-w-4xl mx-auto animate-fadeInUp stagger-1">
        <x-card islamic class="p-6 md:p-8 lg:p-10 bg-gradient-to-br from-amber-50 via-white to-emerald-50">
            {{-- البسملة --}}
            @if($surah->id != 1 && $surah->id != 9)
                <div class="text-center mb-8">
                    <p class="quran-font text-3xl md:text-4xl text-emerald-800">بِسْمِ ٱللَّهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ</p>
                </div>
            @endif

            {{-- Verses --}}
            <div class="quran-content">
                @foreach($verses as $verse)
                    <div class="inline">
                        <span class="quran-font text-2xl md:text-3xl leading-loose text-slate-900">
                            {{ $verse->verse_text }}
                        </span>
                        <span class="verse-number inline-flex items-center justify-center w-7 h-7 mx-1 text-xs font-bold bg-emerald-600 text-white rounded-full shadow-sm">
                            {{ $verse->verse_number }}
                        </span>
                    </div>
                    @if(app()->getLocale() === 'en' && $verse->verse_text_english)
                    <p style="direction: ltr; text-align: left; font-family: 'Segoe UI', sans-serif; font-size: 0.95rem; color: #666; line-height: 1.8; margin: 8px 0 0 0; font-style: italic;">{{ $verse->verse_text_english }}</p>
                    @endif
                @endforeach
            </div>
        </x-card>
    </div>

    {{-- Navigation --}}
    <div class="max-w-4xl mx-auto mt-6 animate-fadeInUp stagger-2">
        <div class="flex justify-between items-center">
            @if($surah->previous())
                <x-button variant="gold" href="{{ route('quran.surah', $surah->previous()->id) }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    {{ app()->getLocale() === 'ar' ? $surah->previous()->name_arabic : $surah->previous()->name_english }}
                </x-button>
            @else
                <div></div>
            @endif

            <a href="{{ route('quran.index') }}" class="text-emerald-700 hover:text-emerald-900 font-semibold">
                {{ app()->getLocale() === 'ar' ? 'العودة للفهرس' : 'Back to Index' }}
            </a>

            @if($surah->next())
                <x-button variant="gold" href="{{ route('quran.surah', $surah->next()->id) }}">
                    {{ app()->getLocale() === 'ar' ? $surah->next()->name_arabic : $surah->next()->name_english }}
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
    line-height: 2.5;
}

.quran-content {
    text-align: justify;
    direction: rtl;
    min-height: 400px;
}

.verse-number {
    vertical-align: middle;
}
</style>
@endsection
