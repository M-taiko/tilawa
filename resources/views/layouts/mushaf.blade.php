<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
 <meta name="csrf-token" content="{{ csrf_token() }}">
 <meta name="description" content="المصحف الكريم - تلاوة">
 <title>@yield('title', 'المصحف الكريم - تلاوة')</title>
 {{-- PWA --}}
 <link rel="manifest" href="/manifest.json">
 <meta name="theme-color" content="#1a0e00">
 <meta name="mobile-web-app-capable" content="yes">
 <meta name="apple-mobile-web-app-capable" content="yes">
 <meta name="apple-mobile-web-app-status-bar-style" content="black">
 <meta name="apple-mobile-web-app-title" content="تلاوة">
 <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
 <link rel="icon" type="image/svg+xml" href="/icons/icon.svg">
 {{-- الخطوط --}}
 <link rel="preconnect" href="https://fonts.googleapis.com">
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 <link href="https://fonts.googleapis.com/css2?family=Amiri+Quran&family=Amiri:wght@400;700&family=Scheherazade+New:wght@400;700&display=swap" rel="stylesheet">
 @vite(['resources/css/app.css', 'resources/js/app.js'])
 @stack('styles')
 <style>
 *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
 html { height: 100%; overflow: hidden; }
 body {
     height: 100%;
     overflow: hidden;
     background: #1a0e00;
     font-family: 'Amiri', serif;
     -webkit-font-smoothing: antialiased;
 }
 </style>
 <script>
 if ('serviceWorker' in navigator) {
     window.addEventListener('load', () => {
         navigator.serviceWorker.register('/sw.js').catch(() => {});
     });
 }
 </script>
</head>
<body>
@yield('content')
@stack('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
