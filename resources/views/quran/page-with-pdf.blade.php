@extends('layouts.mushaf')

@section('title', 'صفحة ' . $pageNumber . ' من المصحف الكريم | تلاوة')

@push('styles')
<style>
[x-cloak] { display: none !important; }

html, body { height: 100%; overflow: hidden; }

.pdf-viewer-container {
    display: flex;
    flex-direction: column;
    height: 100dvh;
    background: linear-gradient(160deg, #2d2520 0%, #3d3530 60%, #2d2520 100%);
}

.top-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 16px;
    background: rgba(0,0,0,0.40);
    border-bottom: 2px solid rgba(201,168,76,0.35);
    flex-shrink: 0;
    z-index: 20;
    gap: 12px;
}

.top-bar-btn {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    border-radius: 6px;
    color: #f0d080;
    font-size: 0.8rem;
    font-family: 'Amiri', serif;
    font-weight: 600;
    background: rgba(201,168,76,0.15);
    border: 1px solid rgba(201,168,76,0.35);
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
}
.top-bar-btn:hover {
    background: rgba(201,168,76,0.30);
    border-color: rgba(201,168,76,0.50);
}

.page-select {
    padding: 6px 10px;
    border-radius: 6px;
    background: rgba(201,168,76,0.15);
    border: 1px solid rgba(201,168,76,0.35);
    color: #f0d080;
    font-size: 0.8rem;
    font-family: 'Amiri', serif;
    font-weight: 700;
    cursor: pointer;
    outline: none;
    max-width: 90px;
    transition: all 0.2s;
}
.page-select:hover {
    background: rgba(201,168,76,0.25);
    border-color: rgba(201,168,76,0.50);
}
.page-select:focus {
    background: rgba(201,168,76,0.30);
    border-color: #f0d080;
}

.pdf-area {
    flex: 1;
    overflow: auto;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 20px 10px;
    position: relative;
}

.pdf-page-wrapper {
    position: relative;
    width: 100%;
    max-width: 90vw;
    aspect-ratio: 720 / 1050;
    background: #ffffff;
    box-shadow: 0 8px 40px rgba(0,0,0,0.3);
}

.pdf-image {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: contain;
}

.verse-overlay {
    position: absolute;
    cursor: pointer;
    transition: background 0.15s, border-color 0.15s;
    background: rgba(201,168,76,0.0);
    border: 1px solid rgba(201,168,76,0.0);
}

.verse-overlay:hover {
    background: rgba(201,168,76,0.12);
    border: 1px solid rgba(201,168,76,0.25);
    border-radius: 3px;
}

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

@keyframes fadeIn { from { opacity:0 } to { opacity:1 } }
@keyframes slideUp { from { transform: translateY(100%) } to { transform: translateY(0) } }

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

.popup-tab-buttons {
    display: flex;
    gap: 8px;
    margin-bottom: 14px;
    border-bottom: 2px solid #e2e8f0;
}

.tab-btn {
    flex: 1;
    padding: 10px 8px;
    border: none;
    background: none;
    color: #64748b;
    font-weight: 600;
    cursor: pointer;
    border-bottom: 3px solid transparent;
    font-family: 'Tajawal', sans-serif;
    font-size: 0.85rem;
    transition: all 0.2s;
}

.tab-btn.active {
    color: #3b82f6;
    border-bottom-color: #3b82f6;
}

.tab-content {
    display: none;
    max-height: 300px;
    overflow-y: auto;
    padding: 12px 0;
}

.tab-content.active {
    display: block;
}

.tafsir-text, .translation-text {
    font-size: 0.85rem;
    line-height: 1.8;
    color: #333;
}

.translation-text {
    direction: ltr;
    text-align: left;
}
</style>
@endpush

@section('content')
<div class="pdf-viewer-container">
    {{-- Top Navigation Bar --}}
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
            <a href="{{ route('quran.page', $pageNumber + 1) }}" class="top-bar-btn">‹</a>
            @endif

            <select class="page-select" onchange="navigateToPage(this.value)">
                @for($i = 1; $i <= 604; $i++)
                    <option value="{{ $i }}" {{ $i == $pageNumber ? 'selected' : '' }}>{{ toArabicNumerals($i) }}</option>
                @endfor
            </select>

            @if($pageNumber > 1)
            <a href="{{ route('quran.page', $pageNumber - 1) }}" class="top-bar-btn">›</a>
            @endif
        </div>
    </div>

    {{-- PDF Viewer --}}
    <div class="pdf-area" id="pdfArea">
        <div class="pdf-page-wrapper" id="pdfPage">
            <img id="pdfImage" class="pdf-image" src="" alt="Page {{ $pageNumber }}" />
            <!-- Verse overlays will be inserted here -->
        </div>
    </div>
</div>

{{-- Verse Details Popup --}}
<div id="verse-popup-overlay" class="verse-popup-overlay" style="display:none;" onclick="closeVersePopup()">
    <div class="verse-popup-sheet" id="verse-popup-sheet" onclick="event.stopPropagation()">
        <div class="popup-verse-text" id="popup-verse-text"></div>
        <div class="popup-verse-meta" id="popup-verse-meta"></div>

        {{-- Tabs for Tafsir and Translation --}}
        <div class="popup-tab-buttons">
            <button class="tab-btn active" onclick="showTab('tafsir')">{{ app()->getLocale() === 'ar' ? 'التفسير' : 'Tafsir' }}</button>
            <button class="tab-btn" onclick="showTab('translation')">{{ app()->getLocale() === 'ar' ? 'الترجمة' : 'Translation' }}</button>
            <button class="tab-btn" onclick="showTab('reasons')">{{ app()->getLocale() === 'ar' ? 'أسباب النزول' : 'Reasons' }}</button>
        </div>

        <div id="tafsir-tab" class="tab-content active">
            <div id="tafsir-content" class="tafsir-text">{{ app()->getLocale() === 'ar' ? 'جاري التحميل...' : 'Loading...' }}</div>
        </div>

        <div id="translation-tab" class="tab-content">
            <div id="translation-content" class="translation-text">{{ app()->getLocale() === 'ar' ? 'جاري التحميل...' : 'Loading...' }}</div>
        </div>

        <div id="reasons-tab" class="tab-content">
            <div id="reasons-content" class="tafsir-text">{{ app()->getLocale() === 'ar' ? 'جاري التحميل...' : 'Loading...' }}</div>
        </div>

        <button onclick="closeVersePopup()" style="width:100%;padding:10px;background:#f1f5f9;border:none;border-radius:8px;margin-top:12px;font-weight:600;cursor:pointer;">
            {{ app()->getLocale() === 'ar' ? 'إغلاق' : 'Close' }}
        </button>
    </div>
</div>

<script>
const pageNumber = {{ $pageNumber }};
const verses = @json($verses);
let currentVerse = null;
const locale = '{{ app()->getLocale() }}';

// Load PDF page image
function loadPdfPage() {
    const pdfImage = document.getElementById('pdfImage');
    pdfImage.src = '/quran-pages/page-' + String(pageNumber).padStart(3, '0') + '.jpg';

    // Create verse overlays
    createVerseOverlays();
}

// Create clickable verse overlays
function createVerseOverlays() {
    const pdfPage = document.getElementById('pdfPage');

    // Get wrapper dimensions for percentage-based positioning
    const wrapper = document.getElementById('pdfImage');
    const wrapperRect = wrapper.getBoundingClientRect();

    verses.forEach((verse, index) => {
        const overlay = document.createElement('div');
        overlay.className = 'verse-overlay';
        overlay.setAttribute('data-verse-id', verse.id);
        overlay.setAttribute('data-surah', verse.surah_id);
        overlay.setAttribute('data-verse', verse.verse_number);
        overlay.title = locale === 'ar'
            ? 'سورة ' + verse.surah.name_arabic + ' - الآية ' + verse.verse_number
            : 'Surah ' + verse.surah.name_english + ' - Verse ' + verse.verse_number;

        // Distribute overlays more intelligently across the page
        // For now, this creates a grid pattern that can be refined with actual PDF coordinates
        const itemsPerRow = 4;
        const row = Math.floor(index / itemsPerRow);
        const col = index % itemsPerRow;

        const leftPercent = (col * 25) + 5;
        const topPercent = (row * 18) + 10;

        overlay.style.left = leftPercent + '%';
        overlay.style.top = topPercent + '%';
        overlay.style.width = '20%';
        overlay.style.height = '12%';

        overlay.onclick = (e) => {
            e.stopPropagation();
            showVersePopup(verse);
        };

        pdfPage.appendChild(overlay);
    });
}

// Show verse details in popup
function showVersePopup(verse) {
    currentVerse = verse;
    document.getElementById('popup-verse-text').textContent = verse.verse_text;

    // Set meta text based on locale
    const verseLabel = locale === 'ar' ? 'الآية' : 'Verse';
    const surahLabel = locale === 'ar' ? 'سورة' : 'Surah';
    const surahName = locale === 'ar' ? verse.surah.name_arabic : verse.surah.name_english;

    document.getElementById('popup-verse-meta').textContent =
        `${surahLabel} ${surahName} — ${verseLabel} ${verse.verse_number}`;

    // Reset tab selection to tafsir
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
    document.querySelector('.tab-btn').classList.add('active');
    document.getElementById('tafsir-tab').classList.add('active');

    // Load verse data
    fetchVerseData(verse);

    // Show popup
    document.getElementById('verse-popup-overlay').style.display = 'flex';
}

// Fetch verse tafsir and translation
function fetchVerseData(verse) {
    // Reset all tabs to loading state
    const tafsirContent = document.getElementById('tafsir-content');
    const translationContent = document.getElementById('translation-content');
    const reasonsContent = document.getElementById('reasons-content');

    const loadingMsg = locale === 'ar' ? 'جاري التحميل...' : 'Loading...';
    tafsirContent.textContent = loadingMsg;
    translationContent.textContent = loadingMsg;
    reasonsContent.textContent = loadingMsg;

    // Load tafsir (ar.muyassar in Arabic, en.sahih in English)
    const tafsirEdition = locale === 'ar' ? 'ar.muyassar' : 'en.sahih';
    fetch(`https://api.alquran.cloud/v1/ayah/${verse.surah_id}:${verse.verse_number}/${tafsirEdition}`)
        .then(r => r.json())
        .then(data => {
            if (data.code === 200) {
                tafsirContent.textContent = data.data.text;
            } else {
                tafsirContent.textContent = locale === 'ar' ? 'لا توجد بيانات' : 'No data available';
            }
        })
        .catch(err => {
            const errorMsg = locale === 'ar' ? 'فشل التحميل' : 'Failed to load';
            tafsirContent.textContent = errorMsg;
        });

    // Load translation (show from database if available, or fetch from API)
    if (locale === 'ar') {
        translationContent.textContent = locale === 'ar' ? 'لا توجد ترجمة عربية' : 'No Arabic translation';
    } else {
        // For English, try database first
        if (verse.verse_text_english) {
            translationContent.textContent = verse.verse_text_english;
        } else {
            // Fallback to API
            fetch(`https://api.alquran.cloud/v1/ayah/${verse.surah_id}:${verse.verse_number}/en.sahih`)
                .then(r => r.json())
                .then(data => {
                    if (data.code === 200) {
                        translationContent.textContent = data.data.text;
                    }
                })
                .catch(err => {
                    translationContent.textContent = locale === 'ar' ? 'فشل التحميل' : 'Failed to load';
                });
        }
    }

    // Load reasons for revelation (Asbab al-Nuzul)
    fetch(`https://api.alquran.cloud/v1/ayah/${verse.surah_id}:${verse.verse_number}/ar.asbabnuzul`)
        .then(r => r.json())
        .then(data => {
            if (data.code === 200 && data.data && data.data.text) {
                reasonsContent.textContent = data.data.text;
            } else {
                reasonsContent.textContent = locale === 'ar'
                    ? 'لا توجد معلومات عن أسباب النزول'
                    : 'No reasons for revelation found';
            }
        })
        .catch(err => {
            const errorMsg = locale === 'ar' ? 'فشل التحميل' : 'Failed to load';
            reasonsContent.textContent = errorMsg;
        });
}

// Tab navigation
function showTab(tabName) {
    // Hide all tabs and deactivate buttons
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));

    // Show selected tab and activate its button
    const tabElement = document.getElementById(tabName + '-tab');
    if (tabElement) {
        tabElement.classList.add('active');
    }

    // Find and activate the corresponding button
    document.querySelectorAll('.tab-btn').forEach(btn => {
        if (btn.onclick.toString().includes(`'${tabName}'`)) {
            btn.classList.add('active');
        }
    });
}

// Close popup
function closeVersePopup() {
    document.getElementById('verse-popup-overlay').style.display = 'none';
    currentVerse = null;
}

// Navigate to page
function navigateToPage(pageNum) {
    if (pageNum && pageNum != pageNumber) {
        window.location.href = '/quran/page/' + pageNum;
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', loadPdfPage);
</script>
@endsection
