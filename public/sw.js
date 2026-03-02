// ===== Tilawa PWA Service Worker v2 =====
const CACHE_VERSION   = 'v2';
const CORE_CACHE      = 'tilawa-core-'   + CACHE_VERSION;
const QURAN_CACHE     = 'tilawa-quran-'  + CACHE_VERSION;
const FONT_CACHE      = 'tilawa-fonts-'  + CACHE_VERSION;
const ALL_CACHES      = [CORE_CACHE, QURAN_CACHE, FONT_CACHE];

// الملفات الأساسية — تُحمَّل فور التثبيت
const CORE_ASSETS = [
    '/quran/',
    '/offline.html',
    '/manifest.json',
];

// ===== Install: cache الأصول الأساسية =====
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CORE_CACHE)
            .then(cache => cache.addAll(CORE_ASSETS))
            .then(() => self.skipWaiting())
    );
});

// ===== Activate: حذف الـ cache القديم =====
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then(keys =>
            Promise.all(
                keys.filter(k => !ALL_CACHES.includes(k))
                    .map(k => caches.delete(k))
            )
        ).then(() => self.clients.claim())
    );
});

// ===== رسائل من الصفحة =====
self.addEventListener('message', (event) => {
    if (event.data?.type === 'CACHE_PAGES') {
        // طلب pre-cache لمجموعة صفحات
        const pages = event.data.pages || [];
        caches.open(QURAN_CACHE).then(cache => {
            pages.forEach(url => {
                fetch(url).then(res => {
                    if (res.ok) cache.put(url, res);
                }).catch(() => {});
            });
        });
    }

    if (event.data?.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});

// ===== Fetch =====
self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);
    if (event.request.method !== 'GET') return;

    // 1. الخطوط الخارجية — Cache First إلى الأبد
    if (
        url.hostname === 'fonts.googleapis.com' ||
        url.hostname === 'fonts.gstatic.com' ||
        url.hostname === 'cdn.jsdelivr.net'
    ) {
        event.respondWith(cacheFirst(event.request, FONT_CACHE));
        return;
    }

    // 2. صفحات القرآن — Stale While Revalidate
    if (
        url.pathname.startsWith('/quran/page/') ||
        url.pathname === '/quran/' ||
        url.pathname === '/quran'
    ) {
        event.respondWith(staleWhileRevalidate(event.request, QURAN_CACHE));
        return;
    }

    // 3. الأصول المبنية (CSS/JS/icons) — Cache First
    if (url.pathname.startsWith('/build/') || url.pathname.startsWith('/icons/')) {
        event.respondWith(cacheFirst(event.request, CORE_CACHE));
        return;
    }

    // 4. الباقي — Network First مع fallback للـ offline page
    event.respondWith(
        fetch(event.request)
            .catch(() =>
                caches.match(event.request)
                    .then(cached => cached || caches.match('/offline.html'))
            )
    );
});

// ===== استراتيجيات الـ Cache =====

async function cacheFirst(request, cacheName) {
    const cache  = await caches.open(cacheName);
    const cached = await cache.match(request);
    if (cached) return cached;
    try {
        const response = await fetch(request);
        if (response.ok) cache.put(request, response.clone());
        return response;
    } catch {
        return cached || new Response('Offline', { status: 503 });
    }
}

async function staleWhileRevalidate(request, cacheName) {
    const cache  = await caches.open(cacheName);
    const cached = await cache.match(request);

    const fetchPromise = fetch(request).then(response => {
        if (response.ok) cache.put(request, response.clone());
        return response;
    }).catch(() => cached || caches.match('/offline.html'));

    return cached || fetchPromise;
}
