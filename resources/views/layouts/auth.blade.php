<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth transition-colors duration-200">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <meta name="description" content="نظام تلاوة - مركز تحفيظ القرآن">
 <title>@yield('title', 'Tilawa - تحفيظ القرآن')</title>
 <link rel="manifest" href="/manifest.json">
 <meta name="theme-color" content="#c9a84c">
 <link rel="preconnect" href="https://fonts.googleapis.com">
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;600;700;800&display=swap" rel="stylesheet">
 @vite('resources/css/app.css')
 <script>
 if ('serviceWorker' in navigator) {
     window.addEventListener('load', () => {
         navigator.serviceWorker.register('/sw.js').catch(() => {});
     });
 }
 </script>
</head>
<body class="text-gray-900 antialiased" style="margin:0;padding:0;">
 {{-- Main Content --}}
 <main>
 @yield('content')
 </main>
</body>
</html>
