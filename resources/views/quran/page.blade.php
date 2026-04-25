@extends('layouts.mushaf')

@section('title', 'صفحة ' . $pageNumber . ' من المصحف الكريم | تلاوة')
@section('meta_description', 'قراءة صفحة ' . $pageNumber . ' من القرآن الكريم بالرسم العثماني — تطبيق تلاوة للقراءة والحفظ.')
@section('canonical', url('/quran/page/' . $pageNumber))

@push('seo')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "WebPage",
  "name": "صفحة {{ $pageNumber }} من المصحف الكريم",
  "url": "{{ url('/quran/page/' . $pageNumber) }}",
  "description": "قراءة صفحة {{ $pageNumber }} من القرآن الكريم بالرسم العثماني",
  "inLanguage": "ar",
  "isPartOf": {
    "@@type": "WebSite",
    "name": "تلاوة",
    "url": "{{ url('/quran') }}"
  }
}
</script>
@if($pageNumber > 1)
<link rel="prev" href="{{ url('/quran/page/' . ($pageNumber - 1)) }}">
@endif
@if($pageNumber < 604)
<link rel="next" href="{{ url('/quran/page/' . ($pageNumber + 1)) }}">
@endif
@endpush

@push('styles')
<style>
[x-cloak] { display: none !important; }

/* ===== خط المصحف العثماني ===== */
@font-face {
    font-family: 'KFGQPC Uthmanic';
    src: url('https://cdn.jsdelivr.net/gh/khaled-11/KFGQPC-Uthmanic-Script-HAFS@main/UthmanicHafs1Ver18.ttf') format('truetype');
    font-display: swap;
}

/* ===== Layout كامل الشاشة ===== */
html, body { height: 100%; }

.mushaf-shell {
    display: flex;
    flex-direction: column;
    height: 100dvh;
    background: linear-gradient(160deg, #2d2520 0%, #3d3530 60%, #2d2520 100%);
    overflow: hidden;
    position: relative;
}

/* ===== شريط التنقل العلوي ===== */
.top-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 6px 12px;
    background: rgba(0,0,0,0.35);
    border-bottom: 1px solid rgba(201,168,76,0.25);
    flex-shrink: 0;
    z-index: 20;
    gap: 8px;
}

.top-bar-btn {
    display: flex;
    align-items: center;
    gap: 4px;
    padding: 5px 10px;
    border-radius: 8px;
    color: #f0d080;
    font-size: 0.75rem;
    font-family: 'Amiri', serif;
    font-weight: 600;
    background: rgba(201,168,76,0.12);
    border: 1px solid rgba(201,168,76,0.3);
    cursor: pointer;
    transition: background 0.2s;
    white-space: nowrap;
}
.top-bar-btn:hover { background: rgba(201,168,76,0.25); }
.top-bar-btn.active { background: rgba(201,168,76,0.35); color: #fde68a; }

.page-select {
    padding: 4px 8px;
    border-radius: 8px;
    background: rgba(201,168,76,0.15);
    border: 1px solid rgba(201,168,76,0.4);
    color: #f0d080;
    font-size: 0.8rem;
    font-family: 'Amiri', serif;
    font-weight: 700;
    cursor: pointer;
    outline: none;
    max-width: 90px;
}
.page-select option { background: #2d1a00; color: #f0d080; }

/* ===== منطقة المصحف (قابلة للـ Swipe) ===== */
.mushaf-area {
    flex: 1;
    overflow-y: auto;
    overflow-x: hidden;
    -webkit-overflow-scrolling: touch;
    position: relative;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: stretch;
}

/* ===== ورقة المصحف ===== */
.mushaf-page {
    background: #ffffff;
    position: relative;
    width: 100%;
    max-width: 720px;
    border-radius: 0;
    transition: transform 0.28s cubic-bezier(.4,0,.2,1), opacity 0.28s ease;
    display: flex;
    flex-direction: column;
    /* Ornate border matching Madinah Quran */
    border: 10px solid #8b6f47;
    border-image: repeating-linear-gradient(
        45deg,
        #8b6f47,
        #8b6f47 2px,
        #d4a574 2px,
        #d4a574 4px,
        #6b5538 4px,
        #6b5538 6px
    ) 10;
    box-shadow:
        0 0 0 1px #6b5538,
        0 8px 40px rgba(0,0,0,0.3),
        inset 0 0 30px rgba(0,0,0,0.01);
}

@media (max-width: 600px) {
    .mushaf-area {
        align-items: stretch;
        overflow: hidden; /* منع الـ scroll على التليفون */
    }
    .mushaf-page {
        max-width: 100%;
        border-radius: 0;
        border-left: none;
        border-right: none;
        box-shadow: inset 0 0 40px rgba(200,160,80,0.06);
        height: 100%;
        overflow: hidden;
    }
}

/* Ornate decorative borders - matching Madinah Quran */
.mushaf-page::before {
    content: '۞  ۞  ۞  ۞  ۞  ۞';
    position: absolute;
    top: 8px;
    left: 0;
    right: 0;
    text-align: center;
    font-family: 'Amiri', serif;
    font-size: 1.2rem;
    color: #8b6f47;
    pointer-events: none;
    z-index: 3;
    font-weight: 700;
}
.mushaf-page::after {
    content: '۞  ۞  ۞  ۞  ۞  ۞';
    position: absolute;
    bottom: 8px;
    left: 0;
    right: 0;
    text-align: center;
    font-family: 'Amiri', serif;
    font-size: 1.2rem;
    color: #8b6f47;
    pointer-events: none;
    z-index: 3;
    font-weight: 700;
}

/* animation أثناء الـ swipe */
.mushaf-page.slide-out-left  { transform: translateX(-60px); opacity: 0; }
.mushaf-page.slide-out-right { transform: translateX(60px);  opacity: 0; }
.mushaf-page.slide-in-left   { transform: translateX(60px);  opacity: 0; }
.mushaf-page.slide-in-right  { transform: translateX(-60px); opacity: 0; }

/* ===== رأس الصفحة ===== */
.mushaf-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 24px 8px;
    border-bottom: 1px solid #d4a574;
    position: relative;
    z-index: 2;
    background: #ffffff;
    margin-top: 20px;
}
.mushaf-header-text {
    font-family: 'Amiri', serif;
    font-size: 0.82rem;
    color: #5d4e1f;
    font-weight: 700;
    letter-spacing: 0.05em;
}

/* ===== عنوان السورة ===== */
.surah-title-banner {
    text-align: center;
    margin: 12px 20px 6px;
    position: relative;
    z-index: 2;
}
.surah-title-inner {
    display: inline-block;
    padding: 6px 36px;
    border-top: 2px solid #c9a84c;
    border-bottom: 2px solid #c9a84c;
    background: linear-gradient(90deg, transparent, rgba(201,168,76,0.08) 30%, rgba(201,168,76,0.08) 70%, transparent);
    position: relative;
}
.surah-title-inner::before,
.surah-title-inner::after {
    content: '❧';
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    color: #c9a84c;
    font-size: 1rem;
}
.surah-title-inner::before { right: 6px; }
.surah-title-inner::after  { left: 6px; transform: translateY(-50%) scaleX(-1); }
.surah-title-name {
    font-family: 'KFGQPC Uthmanic','Amiri Quran','Scheherazade New',serif;
    font-size: 1.35rem;
    color: #3d2b00;
    display: block;
    line-height: 1.5;
}
.surah-title-info {
    font-family: 'Amiri',serif;
    font-size: 0.7rem;
    color: #7a5c1e;
    display: block;
}

/* ===== البسملة ===== */
.basmala {
    text-align: center;
    padding: 4px 0 8px;
    font-family: 'KFGQPC Uthmanic','Amiri Quran','Scheherazade New',serif;
    font-size: 1.8rem;
    color: #1a1200;
    line-height: 2.8;
    position: relative;
    z-index: 2;
}

/* ===== نص القرآن ===== */
.mushaf-body {
    padding: 8px 24px 8px;
    direction: rtl;
    text-align: justify;
    text-align-last: center;
    position: relative;
    z-index: 2;
    word-spacing: 0.12em;
    letter-spacing: 0.04em;
}
:root {
    --quran-font-size: 1.45rem;
    --quran-line-height: 2.8;
    --translation-font-size: 0.85rem;
}

.quran-font {
    font-family: 'KFGQPC Uthmanic','Amiri Quran','Scheherazade New',serif;
    font-size: var(--quran-font-size);
    line-height: var(--quran-line-height);
    color: #000000;
    font-weight: 700;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-rendering: geometricPrecision;
}

/* عندما تكون الترجمة الإنجليزية مفعلة */
html[lang="en"],
html[lang="en"] body {
    --quran-font-size: 1.15rem;
    --quran-line-height: 2.2;
    --translation-font-size: 0.8rem;
}

/* ===== حاوية الآية ===== */
.verse-container {
    display: inline;
    border-radius: 3px;
    cursor: pointer;
    padding: 0 1px;
    transition: background 0.2s;
    -webkit-tap-highlight-color: transparent;
}
.verse-container:active {
    background: rgba(201,168,76,0.2);
}

/* ===== رقم الآية ===== */
.verse-end-marker {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 1.7em;
    height: 1.7em;
    margin: 0 0.18em;
    font-family: 'Amiri', serif;
    font-size: 0.48em;
    color: #4a3920;
    background: radial-gradient(circle at 30% 30%, #f9f5ed 0%, #f0e6d8 50%, #dcc9a8 100%);
    border: 2.5px solid #8b6f47;
    border-radius: 50%;
    vertical-align: middle;
    position: relative;
    top: -0.15em;
    line-height: 1;
    font-weight: 700;
    box-shadow: 0 3px 8px rgba(0,0,0,0.15), inset 0 1px 2px rgba(255,255,255,0.8);
}

/* ===== علامة متابعة القراءة 🔖 ===== */
.verse-bookmark {
    background: linear-gradient(90deg, rgba(234,179,8,0.18), rgba(234,179,8,0.08)) !important;
    border-bottom: 3.5px solid #b45309;
    border-radius: 2px;
    position: relative;
}
.verse-bookmark::after {
    content: '🔖';
    position: absolute;
    top: -1.4em;
    right: 0;
    font-size: 0.55em;
    line-height: 1;
    pointer-events: none;
}

/* ===== highlight المعلم ===== */
.verse-highlighted-start {
    background-color: rgba(16,185,129,0.15);
    border-bottom: 4px solid #059669;
    border-radius: 2px;
}
.verse-highlighted-middle {
    background-color: rgba(59,130,246,0.12);
    border-bottom: 4px solid #3b82f6;
    border-radius: 2px;
}
.verse-highlighted-end {
    background-color: rgba(245,158,11,0.15);
    border-bottom: 4px solid #d97706;
    border-radius: 2px;
}

/* ===== فاصل بين السور ===== */
.surah-divider {
    border: none;
    border-top: 2px solid #8b6f47;
    margin: 14px 0;
    opacity: 0.6;
}

/* ===== ذيل الصفحة ===== */
.mushaf-footer {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 8px 24px 12px;
    border-top: 1px solid #d4a574;
    position: relative;
    z-index: 2;
    gap: 10px;
    background: #ffffff;
    margin-bottom: 20px;
}
.mushaf-page-number {
    font-family: 'Amiri',serif;
    font-size: 0.9rem;
    color: #4a3920;
    font-weight: 700;
    background: radial-gradient(circle at 30% 30%, #f9f5ed 0%, #f0e6d8 50%, #dcc9a8 100%);
    border: 2.5px solid #8b6f47;
    border-radius: 50%;
    width: 2.2rem;
    height: 2.2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 3px 8px rgba(0,0,0,0.15), inset 0 1px 2px rgba(255,255,255,0.8);
}
.mushaf-nav-arrow {
    color: #8b6f47;
    font-size: 1.4rem;
    font-weight: 700;
    cursor: pointer;
    padding: 4px 8px;
    border-radius: 4px;
    transition: background 0.2s;
    user-select: none;
}
.mushaf-nav-arrow:hover { background: rgba(139,111,71,0.15); }

/* ===== Popup الآية ===== */
.verse-popup-overlay {
    position: fixed;
    inset: 0;
    z-index: 100;
    background: rgba(0,0,0,0.55);
    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding: 0 0 env(safe-area-inset-bottom, 0);
    animation: fadeIn 0.18s ease;
}
.verse-popup-overlay.closing {
    animation: fadeOut 0.2s ease forwards;
}
@keyframes fadeIn  { from { opacity:0 } to { opacity:1 } }
@keyframes fadeOut { from { opacity:1 } to { opacity:0 } }

.verse-popup-sheet {
    background: #fff;
    border-radius: 20px 20px 0 0;
    width: 100%;
    max-width: 600px;
    padding: 16px 20px 28px;
    box-shadow: 0 -8px 40px rgba(0,0,0,0.25);
    animation: slideUp 0.22s cubic-bezier(.4,0,.2,1);
    direction: rtl;
}
.verse-popup-sheet.closing {
    animation: slideDown 0.2s cubic-bezier(.4,0,.2,1) forwards;
}
@keyframes slideUp   { from { transform: translateY(100%) } to { transform: translateY(0) } }
@keyframes slideDown { from { transform: translateY(0) }    to { transform: translateY(100%) } }

.popup-handle {
    width: 36px; height: 4px;
    background: #e2e8f0;
    border-radius: 2px;
    margin: 0 auto 14px;
}

.popup-verse-text {
    font-family: 'KFGQPC Uthmanic','Amiri Quran','Scheherazade New',serif;
    font-size: 1.3rem;
    line-height: 2.5;
    color: #1a1200;
    text-align: center;
    margin-bottom: 6px;
    direction: rtl;
}
.popup-verse-meta {
    font-family: 'Amiri',serif;
    font-size: 0.8rem;
    color: #64748b;
    text-align: center;
    margin-bottom: 14px;
}
.popup-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
}
/* Modal التفسير وأسباب النزول */
.quran-modal-overlay {
    position: fixed; inset: 0; z-index: 9999;
    background: rgba(0,0,0,0.7);
    display: flex; align-items: flex-end; justify-content: center;
    padding: 0;
}
.quran-modal-sheet {
    width: 100%; max-width: 520px;
    max-height: 80vh;
    background: #1c1109;
    border-radius: 20px 20px 0 0;
    display: flex; flex-direction: column;
    overflow: hidden;
    animation: slideUp 0.3s ease;
}
@keyframes slideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }
.quran-modal-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 20px;
    border-bottom: 1px solid rgba(201,168,76,0.2);
    flex-shrink: 0;
}
.quran-modal-title {
    font-family: 'Amiri', serif;
    font-size: 1.1rem; font-weight: 700;
    color: #c9a84c;
}
.quran-modal-close {
    background: rgba(255,255,255,0.1); border: none; cursor: pointer;
    width: 32px; height: 32px; border-radius: 50%;
    color: #cbd5e1; font-size: 1.1rem;
    display: flex; align-items: center; justify-content: center;
}
.quran-modal-body {
    flex: 1; overflow-y: auto; padding: 20px;
    font-family: 'Amiri', serif; font-size: 1rem;
    color: #e2d5b5; line-height: 2;
    direction: rtl; text-align: right;
}
.quran-modal-loading {
    text-align: center; padding: 40px;
    color: #64748b; font-size: 0.9rem;
}
.popup-action-btn {
    padding: 11px 8px;
    border-radius: 12px;
    font-family: 'Amiri',serif;
    font-size: 0.85rem;
    font-weight: 700;
    cursor: pointer;
    border: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    transition: opacity 0.2s, transform 0.1s;
}
.popup-action-btn:active { transform: scale(0.97); }
.popup-action-btn .icon { font-size: 1.3rem; }

.btn-bookmark      { background: #fef9c3; color: #92400e; }
.btn-unbookmark    { background: #fee2e2; color: #991b1b; }
.btn-copy          { background: #f0fdf4; color: #166534; }
.btn-tafsir        { background: #eff6ff; color: #1e40af; }
.btn-asbab         { background: #fdf4ff; color: #7e22ce; }
.btn-close         { background: #f1f5f9; color: #475569; grid-column: span 2; }

/* ===== لافتة آخر موضع ===== */
.last-pos-banner {
    position: fixed;
    bottom: env(safe-area-inset-bottom, 12px);
    left: 50%;
    transform: translateX(-50%);
    z-index: 50;
    background: linear-gradient(90deg, #1a0e00, #3d2200);
    border: 1px solid #c9a84c;
    border-radius: 24px;
    padding: 8px 18px;
    color: #f0d080;
    font-family: 'Amiri',serif;
    font-size: 0.82rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.4);
    cursor: pointer;
    white-space: nowrap;
    animation: bannerIn 0.3s cubic-bezier(.4,0,.2,1);
    max-width: 90vw;
}
@keyframes bannerIn { from { opacity:0; transform:translateX(-50%) translateY(20px) } to { opacity:1; transform:translateX(-50%) translateY(0) } }
.last-pos-banner .dismiss { opacity:0.6; font-size:0.75rem; }

/* ===== شاشة التليفون ===== */
@media (max-width: 600px) {
    .mushaf-body {
        padding: 2px 12px 6px;
        flex: 1;
        overflow: hidden;
    }
    .mushaf-header { padding: 4px 14px; }
    .mushaf-footer { padding: 4px 14px 6px; }

    /* الخط يتكيف مع ارتفاع الشاشة */
    .quran-font { font-size: 2.4dvh; line-height: 2.6; }
    .basmala    { font-size: 2.8dvh; line-height: 2.2; padding: 2px 0 4px; }

    .surah-title-banner { margin: 6px 14px 2px; }
    .surah-title-inner  { padding: 3px 28px; }
    .surah-title-name   { font-size: 2.5dvh; }
}
</style>
@endpush

@section('content')
@php
    $buildPageUrl = function($page) use ($highlightInfo) {
        $params = ['pageNumber' => $page];
        if (!empty($highlightInfo['student_id'])) {
            $params['student_id'] = $highlightInfo['student_id'];
            $params['highlight_start'] = $highlightInfo['highlight_start'];
            $params['highlight_end'] = $highlightInfo['highlight_end'];
            $params['mode'] = $highlightInfo['mode'];
        }
        return route('quran.page', $params);
    };
    $juzName   = $page?->juz?->name_arabic ?? '';
    $surahName = $verses->first()?->surah?->name_arabic ?? '';
@endphp

<div class="mushaf-shell" id="mushaf-shell">

    {{-- ===== شريط التنقل العلوي ===== --}}
    <div class="top-bar">

        <a href="{{ route('quran.index') }}" class="top-bar-btn">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
            </svg>
            {{ app()->getLocale() === 'ar' ? 'الفهرس' : 'Index' }}
        </a>

        {{-- Language Toggle --}}
        <div style="display:flex;align-items:center;gap:4px;background:rgba(201,168,76,0.12);border-radius:6px;padding:2px 4px;">
            <form method="POST" action="{{ route('locale.switch') }}" class="inline">
                @csrf
                <input type="hidden" name="locale" value="ar">
                <button type="submit" class="top-bar-btn" style="background:{{ app()->getLocale() === 'ar' ? 'rgba(201,168,76,0.3)' : 'transparent' }};padding:4px 8px;">العربية</button>
            </form>
            <form method="POST" action="{{ route('locale.switch') }}" class="inline">
                @csrf
                <input type="hidden" name="locale" value="en">
                <button type="submit" class="top-bar-btn" style="background:{{ app()->getLocale() === 'en' ? 'rgba(201,168,76,0.3)' : 'transparent' }};padding:4px 8px;">EN</button>
            </form>
        </div>

        <div style="display:flex;align-items:center;gap:6px;">
            @if($pageNumber < 604)
            <a href="{{ $buildPageUrl($pageNumber + 1) }}" class="top-bar-btn" id="btn-next" onclick="return swipeTo(event, 'next')">‹</a>
            @endif

            <select class="page-select" onchange="navigateToPage(this.value)">
                @for($i = 1; $i <= 604; $i++)
                    <option value="{{ $i }}" {{ $i == $pageNumber ? 'selected' : '' }}>{{ toArabicNumerals($i) }}</option>
                @endfor
            </select>

            @if($pageNumber > 1)
            <a href="{{ $buildPageUrl($pageNumber - 1) }}" class="top-bar-btn" id="btn-prev" onclick="return swipeTo(event, 'prev')">›</a>
            @endif
        </div>

        <div style="display:flex;gap:6px;align-items:center;">
            {{-- زر الرجوع لآخر موضع --}}
            <button id="goto-bookmark-btn" class="top-bar-btn hidden" onclick="goToBookmark()" title="{{ app()->getLocale() === 'ar' ? 'الرجوع لآخر موضع' : 'Go to bookmark' }}">
                🔖
            </button>
            {{-- PWA --}}
            <button id="pwa-install-btn" class="top-bar-btn hidden" onclick="installPWA()">📲</button>
        </div>
    </div>

    {{-- Teacher Action Bar --}}
    @if(isset($highlightInfo['mode']) && $highlightInfo['mode'] === 'teacher' && isset($student))
    <div style="padding:6px 12px;flex-shrink:0;" x-data="{ showConfirm: false }">
        <div style="background:#ecfdf5;border:2px solid #34d399;border-radius:10px;padding:10px 14px;display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;">
            <div>
                <div style="font-family:'Amiri',serif;font-weight:700;color:#065f46;font-size:0.9rem;">الطالب: {{ $student->name }}</div>
                <div style="font-size:0.75rem;color:#374151;margin-top:2px;">
                    من الآية <strong style="color:#059669;">{{ $highlightInfo['highlight_start'] }}</strong>
                    إلى <strong style="color:#d97706;">{{ $highlightInfo['highlight_end'] }}</strong>
                </div>
            </div>
            <button @click="showConfirm = true"
                    style="padding:7px 14px;background:#059669;color:#fff;border:none;border-radius:8px;font-family:'Amiri',serif;font-weight:700;font-size:0.85rem;cursor:pointer;">
                ✓ تأكيد الحفظ
            </button>
        </div>

        <div x-show="showConfirm" x-cloak
             style="position:fixed;inset:0;z-index:200;background:rgba(0,0,0,0.6);display:flex;align-items:center;justify-content:center;padding:16px;"
             @click.self="showConfirm = false">
            <div style="background:#fff;border-radius:16px;max-width:400px;width:100%;padding:24px;" @click.stop>
                <h3 style="font-family:'Amiri',serif;font-size:1.1rem;font-weight:700;text-align:center;margin-bottom:10px;">تأكيد الحفظ</h3>
                <p style="font-size:0.85rem;color:#374151;text-align:center;margin-bottom:14px;">
                    الطالب <strong>{{ $student->name }}</strong> حفظ من الآية
                    <strong>{{ $highlightInfo['highlight_start'] }}</strong> إلى <strong>{{ $highlightInfo['highlight_end'] }}</strong>
                </p>
                <form method="POST" action="{{ route('teacher.memorization.confirm', $student) }}">
                    @csrf
                    <input type="hidden" name="surah_id" value="{{ $verses->first()->surah_id ?? '' }}">
                    <input type="hidden" name="start_ayah" value="{{ $highlightInfo['highlight_start'] }}">
                    <input type="hidden" name="end_ayah" value="{{ $highlightInfo['highlight_end'] }}">
                    <input type="hidden" name="page_number" value="{{ $pageNumber }}">
                    <textarea name="notes" rows="2" style="width:100%;padding:8px;border:1px solid #e2e8f0;border-radius:8px;font-size:0.85rem;margin-bottom:12px;direction:rtl;" placeholder="ملاحظات (اختياري)"></textarea>
                    <div style="display:flex;gap:8px;">
                        <button type="button" @click="showConfirm = false"
                                style="flex:1;padding:10px;background:#f1f5f9;border:none;border-radius:8px;font-family:'Amiri',serif;font-weight:600;cursor:pointer;">إلغاء</button>
                        <button type="submit"
                                style="flex:1;padding:10px;background:#059669;color:#fff;border:none;border-radius:8px;font-family:'Amiri',serif;font-weight:700;cursor:pointer;">تأكيد</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- ===== منطقة المصحف ===== --}}
    <div class="mushaf-area" id="mushaf-area">
        <div class="mushaf-page" id="mushaf-page">

            {{-- رأس الصفحة --}}
            <div class="mushaf-header">
                <span class="mushaf-header-text">{{ $juzName }}</span>
                <span class="mushaf-header-text" style="color:#c9a84c;font-size:0.75rem;">۞ تلاوة ۞</span>
                <span class="mushaf-header-text">{{ $surahName }}</span>
            </div>

            {{-- محتوى الصفحة --}}
            <div class="mushaf-body" id="mushaf-body">
                @php $currentSurahId = null; @endphp

                @foreach($verses as $verse)
                    @if($verse->verse_number == 1)
                        @if($currentSurahId !== null)
                            <hr class="surah-divider">
                        @endif
                        @php $currentSurahId = $verse->surah_id; @endphp
                        <div class="surah-title-banner">
                            <div class="surah-title-inner">
                                <span class="surah-title-name">سورة {{ $verse->surah->name_arabic }}</span>
                                <span class="surah-title-info">{{ $verse->surah->ayah_count ? toArabicNumerals($verse->surah->ayah_count) : '' }} آية</span>
                            </div>
                        </div>
                        {{-- البسملة المستقلة: لكل السور ماعدا التوبة (9) والفاتحة (1) --}}
                        @if($verse->surah_id != 9 && $verse->surah_id != 1)
                            <div class="basmala">بِسْمِ ٱللَّهِ ٱلرَّحْمَٰنِ ٱلرَّحِيمِ</div>
                        @endif
                    @endif

                    @php
                        $isFatihaBasmala = ($verse->surah_id == 1 && $verse->verse_number == 1);
                        $verseText = $verse->verse_text;
                        // نزيل البسملة من بداية نص الآية لو كانت مدمجة فيها
                        if (!$isFatihaBasmala && $basmalaPrefix && str_starts_with($verseText, $basmalaPrefix)) {
                            $verseText = substr($verseText, strlen($basmalaPrefix));
                        }
                    @endphp

                    @if($isFatihaBasmala)
                        <div class="basmala">{{ $verseText }}</div>
                    @else
                        <span class="verse-container quran-font"
                              data-verse-number="{{ $verse->verse_number }}"
                              data-surah-id="{{ $verse->surah_id }}"
                              data-surah-name="{{ $verse->surah->name_arabic }}"
                              data-surah-name-en="{{ $verse->surah->name_english }}"
                              onclick="showVersePopup(this)">{{ $verseText }}<span class="verse-end-marker">{{ $verse->verse_number }}</span></span>
                        @if(app()->getLocale() === 'en' && $verse->verse_text_english)
                        <p class="verse-translation" style="direction: ltr; text-align: left; font-family: 'Segoe UI', sans-serif; font-size: var(--translation-font-size); color: #888; line-height: 1.7; margin: 6px 0 12px 0; font-style: italic; padding-left: 12px; border-left: 2px solid #d4a574;">{{ $verse->verse_text_english }}</p>
                        @endif
                    @endif

                @endforeach
            </div>

            {{-- ذيل الصفحة --}}
            <div class="mushaf-footer">
                @if($pageNumber < 604)
                <span class="mushaf-nav-arrow" onclick="navigatePage('next')">‹</span>
                @endif

                <div class="mushaf-page-number">{{ toArabicNumerals($pageNumber) }}</div>

                @if($pageNumber > 1)
                <span class="mushaf-nav-arrow" onclick="navigatePage('prev')">›</span>
                @endif
            </div>
        </div>
    </div>

</div>

{{-- ===== Popup الآية ===== --}}
<div id="verse-popup-overlay" class="verse-popup-overlay" style="display:none;" onclick="closeVersePopup()">
    <div class="verse-popup-sheet" id="verse-popup-sheet" onclick="event.stopPropagation()">
        <div class="popup-handle"></div>
        <div class="popup-verse-text" id="popup-verse-text"></div>
        <div class="popup-verse-meta" id="popup-verse-meta"></div>
        <div class="popup-actions">
            <button class="popup-action-btn btn-bookmark" id="popup-bookmark-btn" onclick="toggleBookmark()">
                <span class="icon">🔖</span>
                <span id="popup-bookmark-label">{{ app()->getLocale() === 'ar' ? 'حفظ موضع القراءة' : 'Save Position' }}</span>
            </button>
            <button class="popup-action-btn btn-copy" onclick="copyVerse()">
                <span class="icon">📋</span>
                {{ app()->getLocale() === 'ar' ? 'نسخ الآية' : 'Copy Verse' }}
            </button>
            <button class="popup-action-btn btn-tafsir" onclick="openTafsir()">
                <span class="icon">📖</span>
                {{ app()->getLocale() === 'ar' ? 'التفسير' : 'Translation' }}
            </button>
            <button class="popup-action-btn btn-asbab" onclick="openAsbab()">
                <span class="icon">🌙</span>
                {{ app()->getLocale() === 'ar' ? 'أسباب النزول' : 'Reasons' }}
            </button>
            <button class="popup-action-btn btn-close" onclick="closeVersePopup()">
                {{ app()->getLocale() === 'ar' ? 'إغلاق' : 'Close' }}
            </button>
        </div>
    </div>
</div>

{{-- Modal التفسير / أسباب النزول --}}
<div id="quran-modal-overlay" class="quran-modal-overlay" style="display:none;" onclick="closeQuranModal()">
    <div class="quran-modal-sheet" onclick="event.stopPropagation()">
        <div class="quran-modal-header">
            <span class="quran-modal-title" id="quran-modal-title">التفسير</span>
            <button class="quran-modal-close" onclick="closeQuranModal()">✕</button>
        </div>
        <div class="quran-modal-body" id="quran-modal-body">
            <div class="quran-modal-loading">جاري التحميل...</div>
        </div>
    </div>
</div>

<script>
const PAGE_NUM   = {{ $pageNumber }};
const PREV_URL   = {!! $pageNumber > 1 ? json_encode($buildPageUrl($pageNumber - 1)) : 'null' !!};
const NEXT_URL   = {!! $pageNumber < 604 ? json_encode($buildPageUrl($pageNumber + 1)) : 'null' !!};
const BOOKMARK_KEY = 'tilawa_bookmark';

// تحويل الأرقام للعربية
const _AR = {'0':'٠','1':'١','2':'٢','3':'٣','4':'٤','5':'٥','6':'٦','7':'٧','8':'٨','9':'٩'};
const toAr = n => String(n).replace(/[0-9]/g, d => _AR[d]);

// ========== التنقل ==========
function navigateToPage(n) {
    const url = new URL(window.location.href);
    url.pathname = '/quran/page/' + n;
    window.location.href = url.toString();
}

function navigatePage(dir) {
    const url = dir === 'prev' ? PREV_URL : NEXT_URL;
    if (!url) return;
    const page = document.getElementById('mushaf-page');
    page.classList.add(dir === 'next' ? 'slide-out-right' : 'slide-out-left');
    setTimeout(() => { window.location.href = url; }, 250);
}

function swipeTo(e, dir) {
    e.preventDefault();
    navigatePage(dir);
    return false;
}

// ========== Swipe بالإصبع ==========
(function() {
    let startX = 0, startY = 0, moved = false;
    const area = document.getElementById('mushaf-area');

    area.addEventListener('touchstart', e => {
        startX = e.touches[0].clientX;
        startY = e.touches[0].clientY;
        moved = false;
    }, { passive: true });

    area.addEventListener('touchmove', e => {
        const dx = e.touches[0].clientX - startX;
        const dy = Math.abs(e.touches[0].clientY - startY);
        if (Math.abs(dx) > 10 && dy < 60) moved = true;
    }, { passive: true });

    area.addEventListener('touchend', e => {
        if (!moved) return;
        const dx = e.changedTouches[0].clientX - startX;
        const dy = Math.abs(e.changedTouches[0].clientY - startY);
        if (Math.abs(dx) < 50 || dy > 80) return;

        // المصحف العربي: swipe يمين = صفحة تالية (تقليب للأمام)، swipe يسار = سابقة
        if (dx > 50)  navigatePage('next');
        else if (dx < -50) navigatePage('prev');
    });
})();

// ========== Popup الآية ==========
let activeVerseEl = null;

function showVersePopup(el) {
    activeVerseEl = el;
    const verseNum  = el.dataset.verseNumber;
    const surahName = el.dataset.surahName;
    const surahNameEn = el.dataset.surahNameEn;
    const isEnglish = document.documentElement.lang === 'en';

    // نص الآية (بدون علامة الرقم)
    const textNode = el.childNodes[0];
    const text = textNode ? textNode.textContent.trim() : '';

    document.getElementById('popup-verse-text').textContent = text;
    if (isEnglish) {
        document.getElementById('popup-verse-meta').textContent =
            'Surah ' + surahNameEn + ' — Verse ' + verseNum;
    } else {
        document.getElementById('popup-verse-meta').textContent =
            'سورة ' + surahName + ' - الآية ' + toAr(verseNum);
    }

    // حالة الـ bookmark
    refreshBookmarkBtn();

    const overlay = document.getElementById('verse-popup-overlay');
    overlay.style.display = 'flex';
    overlay.classList.remove('closing');
    document.getElementById('verse-popup-sheet').classList.remove('closing');
}

function closeVersePopup() {
    const overlay = document.getElementById('verse-popup-overlay');
    const sheet   = document.getElementById('verse-popup-sheet');
    overlay.classList.add('closing');
    sheet.classList.add('closing');
    setTimeout(() => { overlay.style.display = 'none'; }, 200);
}

// ========== Modal التفسير وأسباب النزول ==========
function openQuranModal(title, content) {
    document.getElementById('quran-modal-title').textContent = title;
    document.getElementById('quran-modal-body').innerHTML = content;
    document.getElementById('quran-modal-overlay').style.display = 'flex';
    closeVersePopup();
}

function closeQuranModal() {
    document.getElementById('quran-modal-overlay').style.display = 'none';
}

function openTafsir() {
    if (!activeVerseEl) return;
    const surahId  = activeVerseEl.dataset.surahId;
    const verseNum = activeVerseEl.dataset.verseNumber;
    const surahName = activeVerseEl.dataset.surahName;
    const surahNameEn = activeVerseEl.dataset.surahNameEn;
    const isEnglish = document.documentElement.lang === 'en';
    const edition = isEnglish ? 'en.sahih' : 'ar.muyassar';
    const sourceText = isEnglish ? 'Saheeh International Translation' : 'تفسير الميسر';
    const loadingText = isEnglish ? '⏳ Loading translation...' : '⏳ جاري تحميل التفسير...';
    const errorText = isEnglish ? 'Failed to load translation. Try again later.' : 'تعذّر تحميل التفسير. حاول لاحقاً.';

    const title = isEnglish
        ? 'Surah ' + surahNameEn + ' — Verse ' + verseNum
        : 'تفسير سورة ' + surahName + ' - آية ' + toAr(verseNum);

    openQuranModal(title, '<div class="quran-modal-loading">' + loadingText + '</div>');

    fetch(`https://api.alquran.cloud/v1/ayah/${surahId}:${verseNum}/${edition}`)
        .then(r => r.json())
        .then(data => {
            if (data.code === 200) {
                const text = data.data.text;
                const dirAttr = isEnglish ? 'direction: ltr; text-align: left;' : '';
                document.getElementById('quran-modal-body').innerHTML =
                    `<p style="font-size:1.05rem;line-height:2.2;${dirAttr}">${text}</p>
                     <p style="font-size:0.75rem;color:#64748b;margin-top:16px;text-align:center;">Source: ${sourceText}</p>`;
            } else {
                document.getElementById('quran-modal-body').innerHTML =
                    '<p style="color:#ef4444;text-align:center;">' + errorText + '</p>';
            }
        })
        .catch(() => {
            document.getElementById('quran-modal-body').innerHTML =
                '<p style="color:#ef4444;text-align:center;">لا يوجد اتصال بالإنترنت.</p>';
        });
}

function openAsbab() {
    if (!activeVerseEl) return;
    const surahId  = activeVerseEl.dataset.surahId;
    const verseNum = activeVerseEl.dataset.verseNumber;
    const surahName = activeVerseEl.dataset.surahName;

    // أسباب النزول غير متاحة في API مجاني موثوق — نعرض معلومة واضحة
    openQuranModal(
        'أسباب النزول - سورة ' + surahName + ' آية ' + toAr(verseNum),
        `<div style="text-align:center;padding:20px 0;">
            <p style="font-size:2rem;margin-bottom:12px;">📚</p>
            <p style="font-size:0.95rem;color:#c9a84c;font-weight:700;margin-bottom:8px;">
                سورة ${surahName} — الآية ${toAr(verseNum)}
            </p>
            <p style="font-size:0.85rem;color:#94a3b8;line-height:1.9;">
                للاطلاع على أسباب النزول<br>
                يُرجى الرجوع إلى كتب التفسير المعتمدة<br>
                كتفسير ابن كثير والطبري والقرطبي
            </p>
            <a href="https://quran.ksu.edu.sa/tafseer/katheer/${surahId}-${verseNum}.html"
               target="_blank"
               style="display:inline-block;margin-top:16px;padding:10px 20px;background:rgba(201,168,76,0.15);border:1px solid rgba(201,168,76,0.4);border-radius:10px;color:#c9a84c;font-size:0.85rem;text-decoration:none;font-weight:600;">
               📖 تفسير ابن كثير
            </a>
        </div>`
    );
}

// ========== Bookmark (آخر موضع القراءة) ==========
function getBookmark() {
    try { return JSON.parse(localStorage.getItem(BOOKMARK_KEY) || 'null'); } catch { return null; }
}

function refreshBookmarkBtn() {
    const bm = getBookmark();
    const el = activeVerseEl;
    if (!el) return;
    const isCurrentBm = bm && bm.page === PAGE_NUM &&
                        bm.verse == el.dataset.verseNumber &&
                        bm.surah == el.dataset.surahId;
    const hasAnyBm = !!bm;
    const btn   = document.getElementById('popup-bookmark-btn');
    const label = document.getElementById('popup-bookmark-label');
    if (isCurrentBm) {
        // نفس الآية المحفوظة — خيار الإزالة
        btn.className = 'popup-action-btn btn-unbookmark';
        label.textContent = 'إزالة علامة القراءة';
    } else if (hasAnyBm) {
        // يوجد bookmark قديم — سيُستبدل
        btn.className = 'popup-action-btn btn-bookmark';
        label.textContent = 'تحديث موضع القراءة';
    } else {
        btn.className = 'popup-action-btn btn-bookmark';
        label.textContent = 'حفظ موضع القراءة';
    }
}

function toggleBookmark() {
    const el = activeVerseEl;
    if (!el) return;
    const bm = getBookmark();
    const isCurrentBm = bm && bm.page === PAGE_NUM &&
                        bm.verse == el.dataset.verseNumber &&
                        bm.surah == el.dataset.surahId;

    // إزالة visual من الآية القديمة
    document.querySelectorAll('.verse-bookmark').forEach(e => e.classList.remove('verse-bookmark'));

    if (isCurrentBm) {
        localStorage.removeItem(BOOKMARK_KEY);
        document.getElementById('goto-bookmark-btn').classList.add('hidden');
        hideBanner();
    } else {
        const data = {
            page:      PAGE_NUM,
            surah:     parseInt(el.dataset.surahId),
            verse:     parseInt(el.dataset.verseNumber),
            surahName: el.dataset.surahName,
        };
        localStorage.setItem(BOOKMARK_KEY, JSON.stringify(data));
        el.classList.add('verse-bookmark');
        document.getElementById('goto-bookmark-btn').classList.remove('hidden');
    }
    refreshBookmarkBtn();
    closeVersePopup();
}

function copyVerse() {
    const text = document.getElementById('popup-verse-text').textContent;
    navigator.clipboard?.writeText(text).catch(() => {});
    closeVersePopup();
}

// ========== الانتقال للـ Bookmark ==========
function goToBookmark() {
    const bm = getBookmark();
    if (!bm) return;
    if (bm.page !== PAGE_NUM) {
        const url = new URL(window.location.href);
        url.pathname = '/quran/page/' + bm.page;
        window.location.href = url.toString();
        return;
    }
    // scroll للآية
    document.querySelectorAll('.verse-container').forEach(el => {
        if (parseInt(el.dataset.verseNumber) === bm.verse && parseInt(el.dataset.surahId) === bm.surah) {
            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
}

// ========== لافتة العودة لآخر موضع ==========
let bannerTimeout;
function showLastPosBanner(bm) {
    hideBanner();
    const div = document.createElement('div');
    div.id = 'last-pos-banner';
    div.className = 'last-pos-banner';
    div.innerHTML = `
        <span>🔖</span>
        <span>توقفت عند سورة ${bm.surahName} - آية ${toAr(bm.verse)} (ص${toAr(bm.page)})</span>
        <span style="font-size:0.9rem;opacity:0.8;margin-right:4px;">← اضغط للذهاب</span>
        <span class="dismiss" onclick="hideBanner();event.stopPropagation()">✕</span>
    `;
    div.onclick = () => { hideBanner(); goToBookmark(); };
    document.body.appendChild(div);
    bannerTimeout = setTimeout(hideBanner, 7000);
}

function hideBanner() {
    clearTimeout(bannerTimeout);
    const el = document.getElementById('last-pos-banner');
    if (el) el.remove();
}

// ========== تهيئة عند التحميل ==========
document.addEventListener('DOMContentLoaded', function() {

    // === تطبيق الـ bookmark الموجود ===
    const bm = getBookmark();
    if (bm) {
        document.getElementById('goto-bookmark-btn').classList.remove('hidden');

        if (bm.page === PAGE_NUM) {
            // وضع علامة بصرية على الآية
            document.querySelectorAll('.verse-container').forEach(el => {
                if (parseInt(el.dataset.verseNumber) === bm.verse &&
                    parseInt(el.dataset.surahId) === bm.surah) {
                    el.classList.add('verse-bookmark');
                }
            });
        } else {
            // أظهر لافتة العودة
            setTimeout(() => showLastPosBanner(bm), 800);
        }
    }

    // === تحديث bookmark تلقائياً عند مغادرة الصفحة ===
    // (يحفظ آخر صفحة زارها المستخدم كـ "آخر موضع")
    // لا نحفظ تلقائياً - نترك المستخدم يختار عبر الـ popup

    // === highlight المعلم ===
    @if(isset($highlightInfo) && $highlightInfo['student_id'])
    const highlightStart = {{ $highlightInfo['highlight_start'] ?? 'null' }};
    const highlightEnd   = {{ $highlightInfo['highlight_end'] ?? 'null' }};
    if (highlightStart && highlightEnd) {
        let first = null;
        document.querySelectorAll('.verse-container').forEach(el => {
            const v = parseInt(el.dataset.verseNumber);
            if (v === highlightStart) {
                el.classList.add('verse-highlighted-start');
                if (!first) first = el;
            } else if (v === highlightEnd) {
                el.classList.add('verse-highlighted-end');
            } else if (v > highlightStart && v < highlightEnd) {
                el.classList.add('verse-highlighted-middle');
            }
        });
        if (first) setTimeout(() => first.scrollIntoView({ behavior: 'smooth', block: 'center' }), 600);
    }
    @endif

    // animation دخول الصفحة
    const page = document.getElementById('mushaf-page');
    page.style.opacity = '0';
    page.style.transform = 'scale(0.98)';
    requestAnimationFrame(() => {
        page.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        page.style.opacity = '1';
        page.style.transform = 'scale(1)';
    });

    // ===== Pre-cache الصفحات المجاورة + الـ assets =====
    const cachePages = () => {
        if (!('serviceWorker' in navigator) || !navigator.serviceWorker.controller) return;

        // 10 صفحات للأمام + 10 للخلف
        const pagesToCache = [];
        for (let i = PAGE_NUM - 10; i <= PAGE_NUM + 10; i++) {
            if (i >= 1 && i <= 604 && i !== PAGE_NUM)
                pagesToCache.push('/quran/page/' + i);
        }
        if (pagesToCache.length) {
            navigator.serviceWorker.controller.postMessage({
                type: 'CACHE_PAGES',
                pages: pagesToCache,
            });
        }

        // كاش الـ CSS/JS assets الحالية
        const assets = Array.from(document.querySelectorAll('link[rel="stylesheet"], script[src]'))
            .map(el => el.href || el.src)
            .filter(u => u && u.includes('/build/'));
        assets.push('/images/logo.png', '/manifest.json');
        if (assets.length) {
            navigator.serviceWorker.controller.postMessage({
                type: 'CACHE_PAGES',
                pages: assets.map(u => new URL(u).pathname),
            });
        }
    };

    // نستنى لو الـ SW لسه مش active
    if (navigator.serviceWorker.controller) {
        cachePages();
    } else {
        navigator.serviceWorker.addEventListener('controllerchange', cachePages, { once: true });
    }
});

// ========== PWA Install ==========
let deferredPrompt;
window.addEventListener('beforeinstallprompt', e => {
    e.preventDefault();
    deferredPrompt = e;
    document.getElementById('pwa-install-btn').classList.remove('hidden');
});
function installPWA() {
    if (!deferredPrompt) return;
    deferredPrompt.prompt();
    deferredPrompt.userChoice.then(() => {
        deferredPrompt = null;
        document.getElementById('pwa-install-btn').classList.add('hidden');
    });
}

// ========== Font size adjustment for English mode ==========
function adjustFontSize() {
    const lang = document.documentElement.lang || 'ar';
    console.log('Current lang:', lang);
}

// Run on page load
document.addEventListener('DOMContentLoaded', adjustFontSize);

// ========== Keyboard shortcuts ==========
document.addEventListener('keydown', e => {
    if (e.key === 'ArrowRight') navigatePage('prev');
    if (e.key === 'ArrowLeft')  navigatePage('next');
    if (e.key === 'Escape')     closeVersePopup();
});
</script>

@endsection
