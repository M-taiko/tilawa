@extends('layouts.app')

@section('title', 'المصحف الكريم — قراءة القرآن الكريم بالرسم العثماني | تلاوة')
@section('meta_description', 'اقرأ القرآن الكريم كاملاً بالرسم العثماني — 604 صفحة، 114 سورة، 30 جزءاً. تطبيق تلاوة يعمل بدون إنترنت على جميع الأجهزة.')
@section('canonical', url('/quran'))
@section('og_type', 'website')

@push('seo')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "WebApplication",
  "name": "تلاوة — قراءة القرآن الكريم",
  "url": "{{ url('/quran') }}",
  "description": "تطبيق ويب لقراءة القرآن الكريم كاملاً بالرسم العثماني — 604 صفحة، 114 سورة، 30 جزءاً. يعمل بدون إنترنت.",
  "applicationCategory": "ReferenceApplication",
  "operatingSystem": "Any",
  "inLanguage": "ar",
  "author": {
    "@@type": "Organization",
    "name": "Masar Soft",
    "url": "https://masarsoft.io"
  },
  "offers": {
    "@@type": "Offer",
    "price": "0",
    "priceCurrency": "USD"
  }
}
</script>
@endpush

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
<div class="pattern-subtle">
    {{-- شريط علوي: لوجو + لغة + زرار الدخول --}}
    <div class="flex items-center justify-between mb-4 animate-fadeInUp gap-3">
        {{-- لوجو Masar Soft --}}
        <a href="https://masarsoft.io" target="_blank" class="flex items-center gap-2 opacity-80 hover:opacity-100 transition-opacity min-w-0">
            <div class="w-8 h-8 rounded-lg shadow bg-white flex-shrink-0 flex items-center justify-center overflow-hidden" style="padding:2px;">
                <img src="/images/logo.png" alt="Masar Soft" style="width:100%;height:100%;object-fit:contain;">
            </div>
            <span class="text-xs font-bold text-slate-500 hidden sm:inline" style="font-family:'Tajawal',sans-serif;">Masar Soft</span>
        </a>

        {{-- Language Toggle --}}
        <div class="flex-1 flex justify-center">
            <div class="flex items-center gap-1 bg-white rounded-lg p-1 border border-slate-200 shadow-sm">
                <form method="POST" action="{{ route('locale.switch') }}" class="inline">
                    @csrf
                    <input type="hidden" name="locale" value="ar">
                    <button type="submit" class="px-2.5 py-1.5 rounded-md text-xs font-semibold transition-colors {{ app()->getLocale() === 'ar' ? 'bg-slate-100 text-primary-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}" style="font-family:'Tajawal',sans-serif;">
                        العربية
                    </button>
                </form>
                <form method="POST" action="{{ route('locale.switch') }}" class="inline">
                    @csrf
                    <input type="hidden" name="locale" value="en">
                    <button type="submit" class="px-2.5 py-1.5 rounded-md text-xs font-semibold transition-colors {{ app()->getLocale() === 'en' ? 'bg-slate-100 text-primary-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}" style="font-family:'Tajawal',sans-serif;">
                        EN
                    </button>
                </form>
            </div>
        </div>

        {{-- زرار الرجوع للصفحة الرئيسية --}}
        <a href="{{ route('login') }}"
           class="flex-shrink-0 flex items-center gap-1.5 px-3 py-2 rounded-xl bg-white border border-slate-200 shadow-sm text-sm font-semibold text-slate-600 hover:bg-slate-50 hover:shadow-md transition-all"
           style="font-family:'Tajawal',sans-serif;">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            <span class="hidden xs:inline">{{ app()->getLocale() === 'ar' ? 'الصفحة الرئيسية' : 'Home' }}</span>
            <span class="xs:hidden">{{ app()->getLocale() === 'ar' ? 'دخول' : 'Sign In' }}</span>
        </a>
    </div>

    {{-- Islamic Header --}}
    <div class="mb-5 text-center animate-fadeInUp">
        <div class="inline-block p-4 rounded-2xl bg-gradient-to-br from-emerald-50 to-gold-50 border-2 border-gold-300 shadow-lg mb-3">
            <svg class="w-14 h-14 mx-auto text-emerald-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <h1 class="text-2xl md:text-3xl font-bold heading-islamic text-emerald-800 mb-1">{{ app()->getLocale() === 'ar' ? 'المصحف الكريم' : 'The Noble Quran' }}</h1>
            <p class="text-emerald-700 text-sm md:text-base">{{ app()->getLocale() === 'ar' ? 'القرآن الكريم كاملاً بالرسم العثماني' : 'Complete Quran with English Translation' }}</p>
        </div>
    </div>

    {{-- بطاقة تحميل القرآن للعمل Offline --}}
    <div id="offline-download-card" class="mb-5 animate-fadeInUp">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 p-4 rounded-2xl border-2 border-teal-300 bg-gradient-to-r from-teal-50 via-emerald-50 to-teal-50 shadow-md">
            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-teal-500 to-emerald-600 flex items-center justify-center shadow-lg text-white text-2xl">
                ⬇️
            </div>
            <div class="flex-1 min-w-0">
                <div id="offline-status-title" class="font-bold text-teal-900 text-base">{{ app()->getLocale() === 'ar' ? 'تحميل القرآن للقراءة بدون إنترنت' : 'Download Quran for Offline Reading' }}</div>
                <div id="offline-status-desc" class="text-xs text-teal-700 mt-0.5">{{ app()->getLocale() === 'ar' ? 'احفظ القرآن الكريم كاملاً على جهازك واقرأه في أي مكان بدون إنترنت' : 'Save the complete Quran with translations on your device and read it anywhere without internet' }}</div>
                {{-- شريط التقدم --}}
                <div id="offline-progress-bar-wrap" class="hidden mt-2">
                    <div class="w-full bg-teal-200 rounded-full h-2">
                        <div id="offline-progress-bar" class="bg-teal-600 h-2 rounded-full transition-all duration-300" style="width:0%"></div>
                    </div>
                    <div id="offline-progress-text" class="text-xs text-teal-700 mt-1"></div>
                </div>
            </div>
            <div class="flex flex-col gap-2 flex-shrink-0 w-full sm:w-auto">
                <button id="offline-download-btn"
                        onclick="handleOfflineDownload()"
                        class="px-4 py-2 rounded-xl bg-teal-500 hover:bg-teal-600 text-white font-bold text-sm transition-colors shadow cursor-pointer w-full sm:w-auto text-center">
                    {{ app()->getLocale() === 'ar' ? 'تحميل القرآن' : 'Download' }}
                </button>
                <button id="offline-delete-btn"
                        onclick="handleOfflineDelete()"
                        class="hidden px-4 py-1.5 rounded-xl bg-red-100 hover:bg-red-200 text-red-600 text-xs font-semibold transition-colors cursor-pointer w-full sm:w-auto text-center">
                    {{ app()->getLocale() === 'ar' ? 'حذف البيانات' : 'Delete' }}
                </button>
            </div>
        </div>
    </div>

    {{-- بطاقة تثبيت PWA (تظهر فقط إذا لم يكن التطبيق مثبتاً) --}}
    <div id="pwa-install-card" class="hidden mb-5 animate-fadeInUp">
        <div class="flex items-center gap-4 p-4 rounded-2xl border-2 border-emerald-300 bg-gradient-to-r from-emerald-50 via-teal-50 to-emerald-50 shadow-md">
            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg text-white text-2xl">
                📲
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-bold text-emerald-900 text-base">{{ app()->getLocale() === 'ar' ? 'تثبيت التطبيق للقراءة بدون إنترنت' : 'Install App for Offline Reading' }}</div>
                <div class="text-xs text-emerald-700 mt-0.5">{{ app()->getLocale() === 'ar' ? 'ثبّت تلاوة على شاشتك الرئيسية واقرأ القرآن في أي وقت' : 'Install Tilawa on your home screen and read Quran anytime' }}</div>
            </div>
            <div class="flex flex-col gap-2 flex-shrink-0">
                <button id="pwa-install-btn"
                        onclick="installPWA()"
                        class="px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-sm transition-colors shadow cursor-pointer">
                    {{ app()->getLocale() === 'ar' ? 'تثبيت' : 'Install' }}
                </button>
                <button onclick="document.getElementById('pwa-install-card').remove()"
                        class="px-4 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 text-xs transition-colors cursor-pointer">
                    {{ app()->getLocale() === 'ar' ? 'لاحقاً' : 'Later' }}
                </button>
            </div>
        </div>
    </div>

    {{-- بطاقة استكمال القراءة (تظهر فقط إذا كان هناك bookmark) --}}
    <div id="resume-card" class="hidden mb-5 animate-fadeInUp">
        <a id="resume-link" href="#"
           class="group flex items-center gap-3 md:gap-5 p-3 md:p-5 rounded-2xl border-2 border-amber-300 bg-gradient-to-r from-amber-50 via-yellow-50 to-amber-50 hover:from-amber-100 hover:to-yellow-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-0.5">
            <div class="flex-shrink-0 w-10 h-10 md:w-14 md:h-14 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center shadow-lg shadow-amber-300/40 group-hover:scale-105 transition-transform">
                <span style="font-size:1.4rem;line-height:1;">🔖</span>
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-bold text-amber-900 text-sm md:text-lg mb-0.5">{{ app()->getLocale() === 'ar' ? 'استكمال القراءة من حيث توقفت' : 'Continue Reading' }}</div>
                <div id="resume-info" class="text-xs md:text-sm text-amber-700 truncate"></div>
            </div>
            <div class="flex-shrink-0 flex items-center gap-1 md:gap-2 px-3 md:px-4 py-2 rounded-xl bg-amber-500 text-white font-bold text-xs md:text-sm group-hover:bg-amber-600 transition-colors shadow whitespace-nowrap">
                <span>{{ app()->getLocale() === 'ar' ? 'استكمال' : 'Continue' }}</span>
                <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </div>
        </a>
    </div>

    {{-- Quick Access Cards --}}
    <div class="grid grid-cols-3 gap-3 mb-6">
        {{-- السور --}}
        <a href="#surahs" class="group animate-fadeInUp stagger-1">
            <x-card islamic class="h-full p-3 md:p-5 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="text-center">
                    <div class="w-10 h-10 md:w-14 md:h-14 mx-auto mb-2 rounded-xl bg-gradient-to-br from-emerald-100 to-emerald-200 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 md:w-7 md:h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm md:text-base font-bold text-emerald-800">{{ app()->getLocale() === 'ar' ? 'السور' : 'Surahs' }}</h3>
                    <p class="text-slate-500 text-xs hidden sm:block">{{ app()->getLocale() === 'ar' ? '114 سورة' : '114 Surahs' }}</p>
                </div>
            </x-card>
        </a>

        {{-- الأجزاء --}}
        <a href="#juzs" class="group animate-fadeInUp stagger-2">
            <x-card islamic class="h-full p-3 md:p-5 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="text-center">
                    <div class="w-10 h-10 md:w-14 md:h-14 mx-auto mb-2 rounded-xl bg-gradient-to-br from-gold-100 to-gold-200 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 md:w-7 md:h-7 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h3 class="text-sm md:text-base font-bold text-gold-800">{{ app()->getLocale() === 'ar' ? 'الأجزاء' : 'Juzs' }}</h3>
                    <p class="text-slate-500 text-xs hidden sm:block">{{ app()->getLocale() === 'ar' ? '30 جزءاً' : '30 Juzs' }}</p>
                </div>
            </x-card>
        </a>

        {{-- البحث --}}
        <a href="{{ route('quran.search.index') }}" class="group animate-fadeInUp stagger-3">
            <x-card islamic class="h-full p-3 md:p-5 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="text-center">
                    <div class="w-10 h-10 md:w-14 md:h-14 mx-auto mb-2 rounded-xl bg-gradient-to-br from-accent-100 to-accent-200 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5 md:w-7 md:h-7 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm md:text-base font-bold text-accent-800">{{ app()->getLocale() === 'ar' ? 'البحث' : 'Search' }}</h3>
                    <p class="text-slate-500 text-xs hidden sm:block">{{ app()->getLocale() === 'ar' ? 'ابحث في القرآن' : 'Search Quran' }}</p>
                </div>
            </x-card>
        </a>
    </div>

    {{-- السور (Surahs) --}}
    <div id="surahs" class="mb-6">
        <x-card islamic class="overflow-hidden animate-fadeInUp stagger-4">
            <div class="p-6 border-b border-emerald-100 bg-gradient-to-r from-emerald-50 to-gold-50">
                <h2 class="text-2xl font-bold text-emerald-800 flex items-center gap-3">
                    <svg class="w-8 h-8 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ app()->getLocale() === 'ar' ? 'سور القرآن الكريم' : 'Surahs of the Quran' }}
                </h2>
            </div>
            <div class="p-4 md:p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4">
                    @foreach($surahs as $surah)
                        <a href="{{ $surah->start_page ? route('quran.page', ['pageNumber' => $surah->start_page]) : '#' }}"
                           class="group block p-4 rounded-xl border-2 border-emerald-100 hover:border-gold-400 hover:bg-gradient-to-r hover:from-emerald-50 hover:to-gold-50 transition-all duration-300 hover:shadow-md">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                            {{ $surah->id }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="surah-name-uthmanic font-bold text-emerald-900 mb-0.5 truncate">{{ app()->getLocale() === 'ar' ? $surah->name_arabic : $surah->name_english }}</h3>
                                            <p class="text-xs text-slate-500">{{ app()->getLocale() === 'ar' ? $surah->ayah_count . ' آية' : $surah->ayah_count . ' verses' }}</p>
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
    <div id="juzs" class="mb-6">
        <x-card islamic class="overflow-hidden animate-fadeInUp stagger-5">
            <div class="p-6 border-b border-gold-100 bg-gradient-to-r from-gold-50 to-emerald-50">
                <h2 class="text-2xl font-bold text-gold-800 flex items-center gap-3">
                    <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    {{ app()->getLocale() === 'ar' ? 'أجزاء القرآن الكريم' : 'Juzs of the Quran' }}
                </h2>
            </div>
            <div class="p-4 md:p-6">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 lg:grid-cols-6 gap-3">
                    @foreach($juzs as $juz)
                        @php $juzPage = $juz->startSurah?->start_page; @endphp
                        <a href="{{ $juzPage ? route('quran.page', ['pageNumber' => $juzPage]) : '#' }}"
                           class="group block p-4 rounded-xl border-2 border-gold-100 hover:border-emerald-400 hover:bg-gradient-to-br hover:from-gold-50 hover:to-emerald-50 transition-all duration-300 hover:shadow-md text-center">
                            <div class="w-12 h-12 mx-auto mb-2 rounded-full bg-gradient-to-br from-gold-400 to-gold-500 flex items-center justify-center text-white font-bold text-lg shadow-md group-hover:scale-110 transition-transform">
                                {{ $juz->id }}
                            </div>
                            <h3 class="font-bold text-xs text-gold-900 mb-0.5">{{ app()->getLocale() === 'ar' ? $juz->name_arabic : 'Juz ' . $juz->id }}</h3>
                            <p class="surah-name-uthmanic text-xs text-slate-600" style="font-size:0.85rem;">{{ app()->getLocale() === 'ar' ? $juz->startSurah?->name_arabic : $juz->startSurah?->name_english }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </x-card>
    </div>

    {{-- Footer --}}
    <div class="pt-4 pb-4 text-center border-t border-emerald-100 mt-2">
        <p class="text-xs text-slate-400" style="font-family:'Tajawal',sans-serif;">
            {{ app()->getLocale() === 'ar' ? 'صُمِّم بالكامل بواسطة' : 'Designed by' }}
            <a href="https://masarsoft.io" target="_blank" class="text-emerald-600 hover:text-emerald-800 font-semibold transition-colors">masarsoft.io</a>
            &nbsp;•&nbsp; {{ app()->getLocale() === 'ar' ? 'نسألكم الدعاء 🤲' : 'Pray for us 🤲' }}
        </p>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/dexie@3/dist/dexie.min.js"></script>
<script src="/js/quran-offline.js"></script>
<script>
// ===== Offline Download UI =====
(async function () {
    // تحقق من حالة التحميل عند تحميل الصفحة
    if (typeof TilawaOffline === 'undefined') return;

    const downloaded = await TilawaOffline.isQuranDownloaded();
    updateOfflineUI(downloaded);
})();

function updateOfflineUI(isDownloaded) {
    const locale = '{{ app()->getLocale() }}';
    const title  = document.getElementById('offline-status-title');
    const desc   = document.getElementById('offline-status-desc');
    const btn    = document.getElementById('offline-download-btn');
    const delBtn = document.getElementById('offline-delete-btn');

    if (isDownloaded) {
        title.textContent  = locale === 'ar' ? 'القرآن الكريم محفوظ على جهازك ✓' : 'Quran Saved on Your Device ✓';
        desc.textContent   = locale === 'ar' ? 'يمكنك قراءة القرآن الكريم كاملاً حتى بدون إنترنت' : 'You can read the complete Quran even without internet';
        btn.textContent    = locale === 'ar' ? 'تحديث البيانات' : 'Update Data';
        btn.className      = btn.className.replace('bg-teal-500 hover:bg-teal-600', 'bg-emerald-500 hover:bg-emerald-600');
        delBtn.classList.remove('hidden');

        // تحديث التاريخ
        TilawaOffline.getDownloadInfo().then(info => {
            if (info.downloaded_at) {
                const d = new Date(info.downloaded_at);
                if (locale === 'ar') {
                    desc.textContent = `تم التحميل: ${d.toLocaleDateString('ar-EG')} — ${info.verse_count} آية محفوظة`;
                } else {
                    desc.textContent = `Downloaded: ${d.toLocaleDateString('en-US')} — ${info.verse_count} verses saved`;
                }
            }
        });
    } else {
        title.textContent  = locale === 'ar' ? 'تحميل القرآن للقراءة بدون إنترنت' : 'Download Quran for Offline Reading';
        desc.textContent   = locale === 'ar' ? 'احفظ القرآن الكريم كاملاً على جهازك واقرأه في أي مكان بدون إنترنت' : 'Save the complete Quran with translations on your device and read it anywhere without internet';
        btn.textContent    = locale === 'ar' ? 'تحميل القرآن' : 'Download';
        delBtn.classList.add('hidden');
    }
}

async function handleOfflineDownload() {
    const locale = '{{ app()->getLocale() }}';
    if (typeof TilawaOffline === 'undefined') {
        alert(locale === 'ar' ? 'خطأ: مكتبة التخزين غير محملة' : 'Error: Storage library not loaded');
        return;
    }

    const btn          = document.getElementById('offline-download-btn');
    const progressWrap = document.getElementById('offline-progress-bar-wrap');
    const progressBar  = document.getElementById('offline-progress-bar');
    const progressText = document.getElementById('offline-progress-text');
    const desc         = document.getElementById('offline-status-desc');

    btn.disabled   = true;
    btn.textContent = locale === 'ar' ? 'جارٍ التحميل...' : 'Downloading...';
    progressWrap.classList.remove('hidden');

    try {
        await TilawaOffline.downloadQuran((percent, message) => {
            progressBar.style.width = percent + '%';
            progressText.textContent = message;
        });

        progressWrap.classList.add('hidden');
        updateOfflineUI(true);

        // أخبر الـ Service Worker يكاش كل صفحات القرآن في الخلفية
        TilawaOffline.cacheAllPagesViaSW().catch(() => {});
    } catch (err) {
        progressWrap.classList.add('hidden');
        btn.disabled    = false;
        btn.textContent = locale === 'ar' ? 'إعادة المحاولة' : 'Retry';
        desc.textContent = (locale === 'ar' ? 'حدث خطأ أثناء التحميل: ' : 'Download error: ') + err.message;
        console.error('Quran download error:', err);
    }
}

async function handleOfflineDelete() {
    const locale = '{{ app()->getLocale() }}';
    if (!confirm(locale === 'ar' ? 'هل تريد حذف بيانات القرآن المحفوظة؟' : 'Do you want to delete the saved Quran data?')) return;

    await TilawaOffline.deleteQuranData();
    updateOfflineUI(false);

    const btn = document.getElementById('offline-download-btn');
    btn.disabled    = false;
    btn.textContent = locale === 'ar' ? 'تحميل القرآن' : 'Download';
}

// ===== Bookmark =====
(function() {
    const BOOKMARK_KEY = 'tilawa_bookmark';
    const locale = '{{ app()->getLocale() }}';
    try {
        const bm = JSON.parse(localStorage.getItem(BOOKMARK_KEY) || 'null');
        if (!bm || !bm.page) return;
        const card = document.getElementById('resume-card');
        const link = document.getElementById('resume-link');
        const info = document.getElementById('resume-info');
        if (!card || !link || !info) return;
        link.href = '/quran/page/' + bm.page;
        if (locale === 'ar') {
            info.textContent = 'سورة ' + (bm.surahName || '') + ' — الآية ' + bm.verse + ' — صفحة ' + bm.page;
        } else {
            const surahNameEn = bm.surahNameEn || bm.surahName || '';
            info.textContent = 'Surah ' + surahNameEn + ' — Verse ' + bm.verse + ' — Page ' + bm.page;
        }
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
