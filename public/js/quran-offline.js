/**
 * Tilawa Quran Offline Manager
 * يستخدم Dexie.js (IndexedDB) لتخزين القرآن الكريم كاملاً للعمل بدون إنترنت
 */

// ===== تحميل Dexie من CDN (يُضاف في HTML) =====
// نفترض أن Dexie متاح عبر window.Dexie

const DB_NAME    = 'TilawaQuranDB';
const DB_VERSION = 1;

let db = null;

/**
 * تهيئة قاعدة البيانات
 */
async function initDB() {
    if (db) return db;

    db = new Dexie(DB_NAME);

    db.version(DB_VERSION).stores({
        meta:    'key',           // {key: 'version', value: 1}, {key: 'downloaded_at', value: '...'}
        surahs:  'id',            // {id, name_arabic, ayah_count, start_page}
        pages:   'id',            // {id, juz_number, first_surah_id, ...}
        verses:  'id, surah_id, page_number, juz_number', // {id, surah_id, verse_number, verse_text, page_number, juz_number, sajda}
    });

    return db;
}

/**
 * هل القرآن محمّل في IndexedDB؟
 */
async function isQuranDownloaded() {
    try {
        const database = await initDB();
        const versionMeta = await database.meta.get('version');
        if (!versionMeta) return false;
        const count = await database.verses.count();
        return count >= 6000; // القرآن فيه 6236 آية
    } catch {
        return false;
    }
}

/**
 * تحميل القرآن كاملاً وتخزينه في IndexedDB
 * @param {function} onProgress - callback(percent, message)
 */
async function downloadQuran(onProgress) {
    const database = await initDB();

    onProgress?.(5, 'جارٍ الاتصال بالخادم...');

    const response = await fetch('/quran/api/download-all', {
        headers: { 'Accept': 'application/json' }
    });

    if (!response.ok) {
        throw new Error('فشل تحميل البيانات من الخادم');
    }

    onProgress?.(20, 'جارٍ معالجة البيانات...');

    const data = await response.json();

    onProgress?.(40, 'حفظ السور...');
    await database.surahs.bulkPut(data.surahs);

    onProgress?.(55, 'حفظ معلومات الصفحات...');
    await database.pages.bulkPut(data.pages);

    onProgress?.(70, `حفظ ${data.verses.length} آية...`);

    // حفظ الآيات على دفعات لتجنب تجميد المتصفح
    const BATCH = 500;
    for (let i = 0; i < data.verses.length; i += BATCH) {
        await database.verses.bulkPut(data.verses.slice(i, i + BATCH));
        const pct = 70 + Math.round((i / data.verses.length) * 25);
        onProgress?.(pct, `حفظ الآيات... ${i + BATCH > data.verses.length ? data.verses.length : i + BATCH} / ${data.verses.length}`);
    }

    onProgress?.(96, 'اكتمل التحميل...');

    await database.meta.bulkPut([
        { key: 'version',       value: data.version },
        { key: 'downloaded_at', value: new Date().toISOString() },
        { key: 'verse_count',   value: data.verses.length },
    ]);

    onProgress?.(100, 'تم تحميل القرآن الكريم بنجاح ✓');

    return data.verses.length;
}

/**
 * حذف بيانات القرآن من IndexedDB
 */
async function deleteQuranData() {
    const database = await initDB();
    await database.meta.clear();
    await database.surahs.clear();
    await database.pages.clear();
    await database.verses.clear();
}

/**
 * استرجاع آيات صفحة من IndexedDB
 * @param {number} pageNumber
 * @returns {Promise<{verses: Array, pageInfo: object|null, surahs: object}>}
 */
async function getPageFromDB(pageNumber) {
    const database = await initDB();

    const verses = await database.verses
        .where('page_number').equals(pageNumber)
        .sortBy('surah_id');

    // ترتيب الآيات داخل السورة
    verses.sort((a, b) => a.surah_id !== b.surah_id
        ? a.surah_id - b.surah_id
        : a.verse_number - b.verse_number);

    const pageInfo = await database.pages.get(pageNumber) || null;

    // استرجاع بيانات السور المستخدمة في الصفحة
    const surahIds = [...new Set(verses.map(v => v.surah_id))];
    const surahsArr = await database.surahs.bulkGet(surahIds);
    const surahs = {};
    surahsArr.forEach(s => { if (s) surahs[s.id] = s; });

    return { verses, pageInfo, surahs };
}

/**
 * عدد الآيات المحفوظة
 */
async function getStoredVerseCount() {
    try {
        const database = await initDB();
        return await database.verses.count();
    } catch {
        return 0;
    }
}

/**
 * معلومات التحميل
 */
async function getDownloadInfo() {
    try {
        const database = await initDB();
        const downloadedAt = await database.meta.get('downloaded_at');
        const verseCount   = await database.meta.get('verse_count');
        return {
            downloaded_at: downloadedAt?.value || null,
            verse_count:   verseCount?.value   || 0,
        };
    } catch {
        return { downloaded_at: null, verse_count: 0 };
    }
}

/**
 * بعد التحميل: أخبر الـ Service Worker يكاش كل صفحات القرآن
 * بيحصل في background بعد انتهاء الـ download
 */
async function cacheAllPagesViaSW() {
    if (!('serviceWorker' in navigator)) return;
    const sw = navigator.serviceWorker.controller;
    if (!sw) return;

    // أبعت الصفحات على دفعات (50 صفحة كل مرة) عشان ما يثقلش
    const BATCH = 50;
    for (let start = 1; start <= 604; start += BATCH) {
        const pages = [];
        for (let p = start; p < start + BATCH && p <= 604; p++) {
            pages.push('/quran/page/' + p);
        }
        sw.postMessage({ type: 'CACHE_PAGES', pages });
        // انتظر شوية بين كل دفعة
        await new Promise(r => setTimeout(r, 500));
    }
}

// تصدير للاستخدام في الصفحات
window.TilawaOffline = {
    initDB,
    isQuranDownloaded,
    downloadQuran,
    deleteQuranData,
    getPageFromDB,
    getStoredVerseCount,
    getDownloadInfo,
    cacheAllPagesViaSW,
};
