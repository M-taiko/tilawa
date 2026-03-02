// ===== Tilawa PWA Service Worker v5 =====
const CACHE_VERSION = 'v5';
const CORE_CACHE    = 'tilawa-core-'  + CACHE_VERSION;
const QURAN_CACHE   = 'tilawa-quran-' + CACHE_VERSION;
const FONT_CACHE    = 'tilawa-fonts-' + CACHE_VERSION;
const ALL_CACHES    = [CORE_CACHE, QURAN_CACHE, FONT_CACHE];

// الصفحات الأساسية اللي لازم تتكاش فوراً
const CORE_URLS = [
    '/offline.html',
    '/manifest.json',
    '/images/logo.png',
    '/js/quran-offline.js',
];

// أول 30 صفحة من القرآن تتكاش تلقائياً عند التثبيت
const INITIAL_QURAN_PAGES = [];
for (let i = 1; i <= 30; i++) {
    INITIAL_QURAN_PAGES.push('/quran/page/' + i);
}
INITIAL_QURAN_PAGES.push('/quran/');
INITIAL_QURAN_PAGES.push('/quran');

// ===== Install =====
self.addEventListener('install', (event) => {
    event.waitUntil(
        Promise.all([
            // كاش الملفات الأساسية
            caches.open(CORE_CACHE).then(cache => cache.addAll(CORE_URLS)),

            // كاش أول 30 صفحة قرآن في الخلفية (بدون انتظار عشان ما يتأخرش الـ install)
            caches.open(QURAN_CACHE).then(async cache => {
                for (const url of INITIAL_QURAN_PAGES) {
                    try {
                        const res = await fetch(url, { credentials: 'same-origin' });
                        if (res.ok) await cache.put(url, res);
                    } catch { /* ignore */ }
                }
            }),
        ])
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

    // تجاهل طلبات غير HTTP
    if (!url.protocol.startsWith('http')) return;

    // خطوط خارجية وـ CDN — Cache First
    if (
        url.hostname === 'fonts.googleapis.com' ||
        url.hostname === 'fonts.gstatic.com' ||
        url.hostname === 'cdn.jsdelivr.net'
    ) {
        event.respondWith(cacheFirst(req, FONT_CACHE));
        return;
    }

    // CSS / JS (build assets + quran-offline) — Cache First
    if (url.pathname.startsWith('/build/') || url.pathname.startsWith('/js/')) {
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

    // صفحات القرآن — Cache First (يرجع الكاش فوراً، يحدّث في الخلفية)
    if (
        url.pathname.startsWith('/quran/page/') ||
        url.pathname.startsWith('/quran/surah/') ||
        url.pathname.startsWith('/quran/juz/') ||
        url.pathname === '/quran/' ||
        url.pathname === '/quran'
    ) {
        event.respondWith(cacheFirstWithBackground(req, QURAN_CACHE));
        return;
    }

    // باقي الطلبات — Network First مع fallback للكاش
    event.respondWith(
        fetch(req)
            .then(res => {
                if (res.ok) {
                    const clone = res.clone();
                    caches.open(CORE_CACHE).then(c => c.put(req, clone));
                }
                return res;
            })
            .catch(() =>
                caches.match(req)
                    .then(cached => cached || caches.match('/offline.html'))
            )
    );
});

// ===== Cache Strategies =====

// Cache First: يرجع الكاش لو موجود، يجيب من الشبكة لو لأ
async function cacheFirst(request, cacheName) {
    const cache  = await caches.open(cacheName);
    const cached = await cache.match(request);
    if (cached) return cached;
    try {
        const response = await fetch(request, { credentials: 'same-origin' });
        if (response.ok) cache.put(request, response.clone());
        return response;
    } catch {
        const offline = await caches.match('/offline.html');
        return offline || new Response('Offline', { status: 503 });
    }
}

// Cache First مع تحديث في الخلفية
async function cacheFirstWithBackground(request, cacheName) {
    const cache  = await caches.open(cacheName);
    const cached = await cache.match(request);

    // حدّث الكاش في الخلفية دايماً
    const fetchAndUpdate = fetch(request, { credentials: 'same-origin' })
        .then(response => {
            if (response.ok) cache.put(request, response.clone());
            return response;
        })
        .catch(() => null);

    if (cached) {
        // رجّع الكاش فوراً وحدّث في الخلفية
        fetchAndUpdate.catch(() => {});
        return cached;
    }

    // مفيش كاش — انتظر الشبكة
    const networkResponse = await fetchAndUpdate;
    if (networkResponse && networkResponse.ok) return networkResponse;

    // مفيش شبكة ومفيش كاش — offline page
    const offline = await caches.match('/offline.html');
    return offline || new Response('Offline', { status: 503 });
}
