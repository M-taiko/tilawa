// ===== Tilawa PWA Service Worker v6 =====
const CACHE_VERSION = 'v6';
const CORE_CACHE    = 'tilawa-core-'  + CACHE_VERSION;
const QURAN_CACHE   = 'tilawa-quran-' + CACHE_VERSION;
const FONT_CACHE    = 'tilawa-fonts-' + CACHE_VERSION;
const ALL_CACHES    = [CORE_CACHE, QURAN_CACHE, FONT_CACHE];

// الملفات الأساسية اللازمة للعمل offline
const CORE_URLS = [
    '/offline.html',
    '/manifest.json',
    '/images/logo.png',
    '/js/quran-offline.js',
];

// ===== Install =====
// مهم: نكاش بس الملفات الأساسية الصغيرة — لا نبلوك بصفحات القرآن
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CORE_CACHE)
            .then(cache => cache.addAll(CORE_URLS))
            .then(() => self.skipWaiting())
    );
});

// ===== Activate =====
// بعد الـ activate: نبدأ نكاش صفحات القرآن في الخلفية بدون ما نحجب أي حاجة
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then(keys => Promise.all(
                keys.filter(k => !ALL_CACHES.includes(k))
                    .map(k => caches.delete(k))
            ))
            .then(() => self.clients.claim())
            .then(() => precacheQuranPages()) // كاش صفحات القرآن بعد الـ activate
    );
});

// كاش أول 30 صفحة قرآن + الصفحة الرئيسية في الخلفية
async function precacheQuranPages() {
    const cache = await caches.open(QURAN_CACHE);
    const urls = ['/quran/', '/quran'];
    for (let i = 1; i <= 30; i++) urls.push('/quran/page/' + i);

    // كاش بشكل متوازي بدون انتظار — لو فشل في أي صفحة يكمل باقي
    for (const url of urls) {
        fetch(url, { credentials: 'same-origin' })
            .then(res => { if (res.ok) cache.put(url, res.clone()); })
            .catch(() => {});
    }
}

// ===== Messages =====
self.addEventListener('message', (event) => {
    if (event.data?.type === 'CACHE_PAGES') {
        const pages = event.data.pages || [];
        // كاش build assets في CORE_CACHE — باقي الصفحات في QURAN_CACHE
        const buildAssets = pages.filter(u => u.startsWith('/build/') || u.startsWith('/js/'));
        const quranPages  = pages.filter(u => !u.startsWith('/build/') && !u.startsWith('/js/'));

        if (buildAssets.length) {
            caches.open(CORE_CACHE).then(cache => {
                buildAssets.forEach(url => {
                    fetch(url, { credentials: 'same-origin' })
                        .then(res => { if (res.ok) cache.put(url, res); })
                        .catch(() => {});
                });
            });
        }

        if (quranPages.length) {
            caches.open(QURAN_CACHE).then(cache => {
                quranPages.forEach(url => {
                    fetch(url, { credentials: 'same-origin' })
                        .then(res => { if (res.ok) cache.put(url, res); })
                        .catch(() => {});
                });
            });
        }
    }
    if (event.data?.type === 'SKIP_WAITING') self.skipWaiting();
});

// ===== Fetch =====
self.addEventListener('fetch', (event) => {
    const req = event.request;
    if (req.method !== 'GET') return;

    const url = new URL(req.url);
    if (!url.protocol.startsWith('http')) return;

    // خطوط وـ CDN — Cache First
    if (
        url.hostname === 'fonts.googleapis.com' ||
        url.hostname === 'fonts.gstatic.com' ||
        url.hostname === 'cdn.jsdelivr.net'
    ) {
        event.respondWith(cacheFirst(req, FONT_CACHE));
        return;
    }

    // Build assets + JS files — Cache First
    if (url.pathname.startsWith('/build/') || url.pathname.startsWith('/js/')) {
        event.respondWith(cacheFirst(req, CORE_CACHE));
        return;
    }

    // صور وأيقونات — Cache First
    if (url.pathname.startsWith('/icons/') || url.pathname.startsWith('/images/')) {
        event.respondWith(cacheFirst(req, CORE_CACHE));
        return;
    }

    // صفحات القرآن — Cache First (يرجع فوراً، يحدّث في الخلفية)
    if (
        url.pathname.startsWith('/quran/page/') ||
        url.pathname.startsWith('/quran/surah/') ||
        url.pathname.startsWith('/quran/juz/')  ||
        url.pathname === '/quran/'              ||
        url.pathname === '/quran'
    ) {
        event.respondWith(cacheFirstWithBackground(req, QURAN_CACHE));
        return;
    }

    // باقي الطلبات — Network First مع Fallback
    event.respondWith(networkFirstWithFallback(req));
});

// ===== Helpers =====

async function cacheFirst(request, cacheName) {
    const cache  = await caches.open(cacheName);
    const cached = await cache.match(request);
    if (cached) return cached;
    try {
        const response = await fetch(request, { credentials: 'same-origin' });
        if (response.ok) cache.put(request, response.clone());
        return response;
    } catch {
        return (await caches.match('/offline.html')) || new Response('Offline', { status: 503 });
    }
}

async function cacheFirstWithBackground(request, cacheName) {
    const cache  = await caches.open(cacheName);
    const cached = await cache.match(request);

    const networkFetch = fetch(request, { credentials: 'same-origin' })
        .then(res => { if (res.ok) cache.put(request, res.clone()); return res; })
        .catch(() => null);

    if (cached) {
        networkFetch.catch(() => {}); // تحديث الخلفية بدون انتظار
        return cached;
    }

    const net = await networkFetch;
    if (net && net.ok) return net;
    return (await caches.match('/offline.html')) || new Response('Offline', { status: 503 });
}

async function networkFirstWithFallback(request) {
    try {
        const response = await fetch(request);
        if (response.ok) {
            const cache = await caches.open(CORE_CACHE);
            cache.put(request, response.clone());
        }
        return response;
    } catch {
        const cached = await caches.match(request);
        return cached || (await caches.match('/offline.html')) || new Response('Offline', { status: 503 });
    }
}
