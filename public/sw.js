// ===== Tilawa PWA Service Worker v9 =====
const CACHE_VERSION = 'v9';
const CORE_CACHE    = 'tilawa-core-'  + CACHE_VERSION;
const QURAN_CACHE   = 'tilawa-quran-' + CACHE_VERSION;
const FONT_CACHE    = 'tilawa-fonts-' + CACHE_VERSION;
const ALL_CACHES    = [CORE_CACHE, QURAN_CACHE, FONT_CACHE];

// الملفات الثابتة دايماً في الكاش
const STATIC_CORE = [
    '/offline.html',
    '/manifest.json',
    '/images/logo.png',
    '/js/quran-offline.js',
    '/login',
];

// ===== Install =====
// نكاش الـ static files + نكتشف build assets تلقائياً من Vite manifest
self.addEventListener('install', (event) => {
    event.waitUntil(
        buildCoreUrls()
            .then(urls => caches.open(CORE_CACHE).then(cache => {
                // addAll بيفشل كله لو فشل واحد — نستخدم حلقة عشان نتجاهل الأخطاء
                return Promise.all(
                    urls.map(url =>
                        cache.add(url).catch(() => {
                            console.warn('[SW] Failed to cache:', url);
                        })
                    )
                );
            }))
            .then(() => self.skipWaiting())
    );
});

// اكتشاف build assets من Vite manifest تلقائياً
async function buildCoreUrls() {
    const urls = [...STATIC_CORE];
    try {
        const res = await fetch('/build/manifest.json');
        if (res.ok) {
            const manifest = await res.json();
            for (const entry of Object.values(manifest)) {
                if (entry.file) urls.push('/build/' + entry.file);
                if (entry.css)  entry.css.forEach(f => urls.push('/build/' + f));
            }
        }
    } catch { /* ignore */ }
    return urls;
}

// ===== Activate =====
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then(keys => Promise.all(
                keys.filter(k => !ALL_CACHES.includes(k))
                    .map(k => caches.delete(k))
            ))
            .then(() => self.clients.claim())
            .then(() => precacheQuranPagesInBackground())
    );
});

// كاش أول 30 صفحة قرآن في الخلفية بعد الـ activate
function precacheQuranPagesInBackground() {
    const urls = ['/quran/', '/quran'];
    for (let i = 1; i <= 30; i++) urls.push('/quran/page/' + i);

    caches.open(QURAN_CACHE).then(cache => {
        urls.forEach(url => {
            fetch(url, { credentials: 'same-origin' })
                .then(res => { if (res.ok) cache.put(url, res.clone()); })
                .catch(() => {});
        });
    });
}

// ===== Messages =====
self.addEventListener('message', (event) => {
    if (event.data?.type === 'CACHE_PAGES') {
        const pages = event.data.pages || [];
        const buildAssets = pages.filter(u => u.startsWith('/build/') || u.startsWith('/js/'));
        const quranPages  = pages.filter(u => !u.startsWith('/build/') && !u.startsWith('/js/'));

        if (buildAssets.length) {
            caches.open(CORE_CACHE).then(cache =>
                buildAssets.forEach(url =>
                    fetch(url).then(res => { if (res.ok) cache.put(url, res); }).catch(() => {})
                )
            );
        }
        if (quranPages.length) {
            caches.open(QURAN_CACHE).then(cache =>
                quranPages.forEach(url =>
                    fetch(url, { credentials: 'same-origin' })
                        .then(res => { if (res.ok) cache.put(url, res); }).catch(() => {})
                )
            );
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

    // إزالة الـ trailing slash من /quran/ لتجنب ERR_FAILED
    if (url.pathname === '/quran/') {
        event.respondWith(Response.redirect('/quran', 301));
        return;
    }

    // خطوط وـ CDN — Cache First
    if (
        url.hostname === 'fonts.googleapis.com' ||
        url.hostname === 'fonts.gstatic.com'    ||
        url.hostname === 'cdn.jsdelivr.net'
    ) {
        event.respondWith(cacheFirst(req, FONT_CACHE));
        return;
    }

    // Build assets + JS — Cache First (لو مش موجود في الكاش يجيبه ويحفظه)
    if (url.pathname.startsWith('/build/') || url.pathname.startsWith('/js/')) {
        event.respondWith(cacheFirst(req, CORE_CACHE));
        return;
    }

    // صور وأيقونات — Cache First
    if (url.pathname.startsWith('/icons/') || url.pathname.startsWith('/images/')) {
        event.respondWith(cacheFirst(req, CORE_CACHE));
        return;
    }

    // صفحات القرآن — Network First (لو عندك نت → من النت، لو لا → من الكاش)
    if (
        url.pathname.startsWith('/quran/page/')  ||
        url.pathname.startsWith('/quran/surah/') ||
        url.pathname.startsWith('/quran/juz/')   ||
        url.pathname === '/quran/'               ||
        url.pathname === '/quran'
    ) {
        event.respondWith(networkFirstQuran(req, QURAN_CACHE));
        return;
    }

    // صفحة الـ login — Cache First
    if (url.pathname === '/login') {
        event.respondWith(cacheFirst(req, CORE_CACHE));
        return;
    }

    // باقي الطلبات — Network First مع Fallback
    event.respondWith(networkFirstWithFallback(req));
});

// ===== Strategies =====

async function cacheFirst(request, cacheName) {
    const cache  = await caches.open(cacheName);
    const cached = await cache.match(request.url) || await cache.match(request);
    if (cached) return cached;
    try {
        const response = await fetch(request.url, { credentials: 'same-origin' });
        if (response.ok) cache.put(request.url, response.clone());
        return response;
    } catch {
        return (await caches.match('/offline.html'))
            || new Response('Offline', { status: 503, headers: { 'Content-Type': 'text/plain' } });
    }
}

// Network First للقرآن: لو عندك نت → من النت وتكاش في الخلفية، لو لا نت → من الكاش
async function networkFirstQuran(request, cacheName) {
    const cache = await caches.open(cacheName);
    try {
        const response = await fetch(request.url, { credentials: 'same-origin' });
        if (response.ok) {
            cache.put(request.url, response.clone());
        }
        return response;
    } catch {
        // مفيش نت → رجّع من الكاش
        const cached = await cache.match(request.url)
                    || await cache.match(request);
        if (cached) return cached;
        return (await caches.match('/offline.html'))
            || new Response('Offline', { status: 503, headers: { 'Content-Type': 'text/plain' } });
    }
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
        return cached
            || (await caches.match('/offline.html'))
            || new Response('Offline', { status: 503, headers: { 'Content-Type': 'text/plain' } });
    }
}
