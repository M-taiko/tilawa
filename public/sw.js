// ===== Tilawa PWA Service Worker v3 =====
const CACHE_VERSION = 'v3';
const CORE_CACHE    = 'tilawa-core-'  + CACHE_VERSION;
const QURAN_CACHE   = 'tilawa-quran-' + CACHE_VERSION;
const FONT_CACHE    = 'tilawa-fonts-' + CACHE_VERSION;
const ALL_CACHES    = [CORE_CACHE, QURAN_CACHE, FONT_CACHE];

// ===== Install =====
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CORE_CACHE)
            .then(cache => cache.addAll([
                '/offline.html',
                '/manifest.json',
                '/images/logo.png',
            ]))
            .then(() => self.skipWaiting())
    );
});

// ===== Activate =====
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then(keys => Promise.all(
                keys.filter(k => !ALL_CACHES.includes(k))
                    .map(k => caches.delete(k))
            ))
            .then(() => self.clients.claim())
    );
});

// ===== Messages =====
self.addEventListener('message', (event) => {
    if (event.data?.type === 'CACHE_PAGES') {
        const pages = event.data.pages || [];
        caches.open(QURAN_CACHE).then(cache => {
            pages.forEach(url => {
                fetch(url, { credentials: 'same-origin' })
                    .then(res => { if (res.ok) cache.put(url, res); })
                    .catch(() => {});
            });
        });
    }
    if (event.data?.type === 'SKIP_WAITING') self.skipWaiting();
});

// ===== Fetch =====
self.addEventListener('fetch', (event) => {
    const req = event.request;
    if (req.method !== 'GET') return;

    const url = new URL(req.url);

    // خطوط خارجية — Cache First
    if (
        url.hostname === 'fonts.googleapis.com' ||
        url.hostname === 'fonts.gstatic.com' ||
        url.hostname === 'cdn.jsdelivr.net'
    ) {
        event.respondWith(cacheFirst(req, FONT_CACHE));
        return;
    }

    // CSS / JS (build assets) — Cache First ثم network
    if (url.pathname.startsWith('/build/')) {
        event.respondWith(cacheFirst(req, CORE_CACHE));
        return;
    }

    // صور وأيقونات — Cache First
    if (
        url.pathname.startsWith('/icons/') ||
        url.pathname.startsWith('/images/')
    ) {
        event.respondWith(cacheFirst(req, CORE_CACHE));
        return;
    }

    // صفحات القرآن — Cache First مع revalidate في الخلفية
    if (
        url.pathname.startsWith('/quran/page/') ||
        url.pathname === '/quran/' ||
        url.pathname === '/quran'
    ) {
        event.respondWith(staleWhileRevalidate(req, QURAN_CACHE));
        return;
    }

    // باقي الطلبات — Network First مع fallback
    event.respondWith(
        fetch(req).then(res => {
            // كاش CSS/JS اللي بيجي مع الصفحات ديناميكياً
            if (res.ok && (
                url.pathname.startsWith('/build/') ||
                url.pathname.startsWith('/icons/') ||
                url.pathname.startsWith('/images/')
            )) {
                caches.open(CORE_CACHE).then(c => c.put(req, res.clone()));
            }
            return res;
        }).catch(() =>
            caches.match(req).then(cached => cached || caches.match('/offline.html'))
        )
    );
});

// ===== Cache Strategies =====

async function cacheFirst(request, cacheName) {
    const cache  = await caches.open(cacheName);
    const cached = await cache.match(request);
    if (cached) return cached;
    try {
        const response = await fetch(request, { credentials: 'same-origin' });
        if (response.ok) cache.put(request, response.clone());
        return response;
    } catch {
        return caches.match('/offline.html').then(r => r || new Response('Offline', { status: 503 }));
    }
}

async function staleWhileRevalidate(request, cacheName) {
    const cache  = await caches.open(cacheName);
    const cached = await cache.match(request);

    const fetchPromise = fetch(request, { credentials: 'same-origin' })
        .then(response => {
            if (response.ok) cache.put(request, response.clone());
            return response;
        })
        .catch(() => cached || caches.match('/offline.html'));

    // لو موجود في الكاش رجّعه فوراً وحدّث في الخلفية
    return cached || fetchPromise;
}
