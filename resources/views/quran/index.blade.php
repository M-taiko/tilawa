@extends('layouts.app')

@section('title', 'المصحف الكريم - Tilawa')

@push('styles')
<style>
@font-face {
    font-family: 'KFGQPC Uthmanic';
    src: url('https://cdn.jsdelivr.net/gh/khaled-11/KFGQPC-Uthmanic-Script-HAFS@main/UthmanicHafs1Ver18.ttf') format('truetype');
    font-display: swap;
}
.surah-name-uthmanic {
    font-family: 'KFGQPC Uthmanic', 'Amiri Quran', 'Scheherazade New', serif;
    font-size: 1.15rem;
    line-height: 1.6;
}
</style>
@endpush

@section('content')
<div class="min-h-screen p-4 md:p-6 pattern-subtle">
    {{-- Islamic Header --}}
    <div class="mb-6 md:mb-8 text-center animate-fadeInUp">
        <div class="inline-block p-4 md:p-6 rounded-2xl bg-gradient-to-br from-emerald-50 to-gold-50 border-2 border-gold-300 shadow-lg mb-4">
            <svg class="w-16 h-16 md:w-20 md:h-20 mx-auto text-emerald-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <h1 class="text-3xl md:text-4xl font-bold heading-islamic text-emerald-800 mb-2">المصحف الكريم</h1>
            <p class="text-emerald-700 text-lg">القرآن الكريم كاملاً بالرسم العثماني</p>
        </div>
    </div>

    {{-- بطاقة تثبيت PWA (تظهر فقط إذا لم يكن التطبيق مثبتاً) --}}
    <div id="pwa-install-card" class="hidden max-w-6xl mx-auto mb-6 animate-fadeInUp">
        <div class="flex items-center gap-4 p-4 rounded-2xl border-2 border-emerald-300 bg-gradient-to-r from-emerald-50 via-teal-50 to-emerald-50 shadow-md">
            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg text-white text-2xl">
                📲
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-bold text-emerald-900 text-base">تثبيت التطبيق للقراءة بدون إنترنت</div>
                <div class="text-xs text-emerald-700 mt-0.5">ثبّت تلاوة على شاشتك الرئيسية واقرأ القرآن في أي وقت</div>
            </div>
            <div class="flex flex-col gap-2 flex-shrink-0">
                <button id="pwa-install-btn"
                        onclick="installPWA()"
                        class="px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-sm transition-colors shadow cursor-pointer">
                    تثبيت
                </button>
                <button onclick="document.getElementById('pwa-install-card').remove()"
                        class="px-4 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 text-xs transition-colors cursor-pointer">
                    لاحقاً
                </button>
            </div>
        </div>
    </div>

    {{-- بطاقة استكمال القراءة (تظهر فقط إذا كان هناك bookmark) --}}
    <div id="resume-card" class="hidden max-w-6xl mx-auto mb-6 animate-fadeInUp">
        <a id="resume-link" href="#"
           class="group flex items-center gap-3 md:gap-5 p-3 md:p-5 rounded-2xl border-2 border-amber-300 bg-gradient-to-r from-amber-50 via-yellow-50 to-amber-50 hover:from-amber-100 hover:to-yellow-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-0.5">
            <div class="flex-shrink-0 w-10 h-10 md:w-14 md:h-14 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-lg shadow-amber-300/40 group-hover:scale-105 transition-transform">
                <span style="font-size:1.4rem;line-height:1;">🔖</span>
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-bold text-amber-900 text-sm md:text-lg mb-0.5">استكمال القراءة من حيث توقفت</div>
                <div id="resume-info" class="text-xs md:text-sm text-amber-700 truncate"></div>
            </div>
            <div class="flex-shrink-0 flex items-center gap-1 md:gap-2 px-3 md:px-4 py-2 rounded-xl bg-amber-500 text-white font-bold text-xs md:text-sm group-hover:bg-amber-600 transition-colors shadow whitespace-nowrap">
                <span>استكمال</span>
                <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </div>
        </a>
    </div>

    {{-- Quick Access Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-8 max-w-6xl mx-auto">
        {{-- السور --}}
        <a href="#surahs" class="group animate-fadeInUp stagger-1">
            <x-card islamic class="h-full p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-xl bg-gradient-to-br from-emerald-100 to-emerald-200 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-emerald-800 mb-2">السور</h3>
                    <p class="text-slate-600">تصفح 114 سورة</p>
                </div>
            </x-card>
        </a>

        {{-- الأجزاء --}}
        <a href="#juzs" class="group animate-fadeInUp stagger-2">
            <x-card islamic class="h-full p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-xl bg-gradient-to-br from-gold-100 to-gold-200 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gold-800 mb-2">الأجزاء</h3>
                    <p class="text-slate-600">تصفح 30 جزءاً</p>
                </div>
            </x-card>
        </a>

        {{-- البحث --}}
        <a href="{{ route('quran.search.index') }}" class="group animate-fadeInUp stagger-3">
            <x-card islamic class="h-full p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-xl bg-gradient-to-br from-accent-100 to-accent-200 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-accent-800 mb-2">البحث</h3>
                    <p class="text-slate-600">ابحث في القرآن</p>
                </div>
            </x-card>
        </a>
    </div>

    {{-- السور (Surahs) --}}
    <div id="surahs" class="max-w-7xl mx-auto mb-8">
        <x-card islamic class="overflow-hidden animate-fadeInUp stagger-4">
            <div class="p-6 border-b border-emerald-100 bg-gradient-to-r from-emerald-50 to-gold-50">
                <h2 class="text-2xl font-bold text-emerald-800 flex items-center gap-3">
                    <svg class="w-8 h-8 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    سور القرآن الكريم
                </h2>
            </div>
            <div class="p-4 md:p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4">
                    @foreach($surahs as $surah)
                        <a href="{{ route('quran.page', ['pageNumber' => $surah->start_page]) }}"
                           class="group block p-4 rounded-xl border-2 border-emerald-100 hover:border-gold-400 hover:bg-gradient-to-r hover:from-emerald-50 hover:to-gold-50 transition-all duration-300 hover:shadow-md">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                            {{ $surah->id }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="surah-name-uthmanic font-bold text-emerald-900 mb-0.5 truncate">{{ $surah->name_arabic }}</h3>
                                            <p class="text-xs text-slate-500">{{ $surah->ayah_count }} آية</p>
                                        </div>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-gold-500 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </x-card>
    </div>

    {{-- الأجزاء (Juzs) --}}
    <div id="juzs" class="max-w-7xl mx-auto">
        <x-card islamic class="overflow-hidden animate-fadeInUp stagger-5">
            <div class="p-6 border-b border-gold-100 bg-gradient-to-r from-gold-50 to-emerald-50">
                <h2 class="text-2xl font-bold text-gold-800 flex items-center gap-3">
                    <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    أجزاء القرآن الكريم
                </h2>
            </div>
            <div class="p-4 md:p-6">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-6 gap-3">
                    @foreach($juzs as $juz)
                        @php $juzPage = $juz->startSurah?->start_page ?? (($juz->id - 1) * 20 + 1); @endphp
                        <a href="{{ route('quran.page', ['pageNumber' => $juzPage]) }}"
                           class="group block p-4 rounded-xl border-2 border-gold-100 hover:border-emerald-400 hover:bg-gradient-to-br hover:from-gold-50 hover:to-emerald-50 transition-all duration-300 hover:shadow-md text-center">
                            <div class="w-12 h-12 mx-auto mb-2 rounded-full bg-gradient-to-br from-gold-400 to-gold-500 flex items-center justify-center text-white font-bold text-lg shadow-md group-hover:scale-110 transition-transform">
                                {{ $juz->id }}
                            </div>
                            <h3 class="font-bold text-xs text-gold-900 mb-0.5">{{ $juz->name_arabic }}</h3>
                            <p class="surah-name-uthmanic text-xs text-slate-600" style="font-size:0.85rem;">{{ $juz->startSurah->name_arabic }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </x-card>
    </div>
</div>
<script>
// ===== Bookmark =====
(function() {
    const BOOKMARK_KEY = 'tilawa_bookmark';
    try {
        const bm = JSON.parse(localStorage.getItem(BOOKMARK_KEY) || 'null');
        if (!bm || !bm.page) return;
        const card = document.getElementById('resume-card');
        const link = document.getElementById('resume-link');
        const info = document.getElementById('resume-info');
        if (!card || !link || !info) return;
        link.href = '/quran/page/' + bm.page;
        info.textContent = 'سورة ' + (bm.surahName || '') + ' — الآية ' + bm.verse + ' — صفحة ' + bm.page;
        card.classList.remove('hidden');
    } catch(e) {}
})();

// ===== PWA Install =====
let deferredPrompt = null;

window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
    // أظهر الكارت فقط لو التطبيق مش مثبت
    const card = document.getElementById('pwa-install-card');
    if (card) card.classList.remove('hidden');
});

function installPWA() {
    if (!deferredPrompt) return;
    deferredPrompt.prompt();
    deferredPrompt.userChoice.then((result) => {
        deferredPrompt = null;
        const card = document.getElementById('pwa-install-card');
        if (card) card.remove();
    });
}

// لو فاتح من PWA أو مثبت → أخفي الكارت
if (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone) {
    const card = document.getElementById('pwa-install-card');
    if (card) card.remove();
}
</script>

@endsection
