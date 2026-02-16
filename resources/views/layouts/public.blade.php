<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth transition-colors duration-200">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <meta name="csrf-token" content="{{ csrf_token() }}">
 <meta name="description" content="نظام تلاوة - مركز تحفيظ القرآن">
 <title>@yield('title', 'Tilawa - تحفيظ القرآن')</title>
 <link rel="preconnect" href="https://fonts.googleapis.com">
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;600;700;800&display=swap" rel="stylesheet">
 @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen antialiased">
 {{-- Background Decorations --}}
 <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
 <div class="absolute -top-40 -right-32 h-[480px] w-[480px] rounded-full bg-gradient-to-br from-primary-200/40 to-accent-200/40 blur-3xl"></div>
 <div class="absolute top-56 -left-24 h-[480px] w-[480px] rounded-full bg-gradient-to-br from-accent-200/40 to-primary-200/40 blur-3xl"></div>
 </div>

 {{-- Main Content --}}
 <main class="min-h-screen px-6 py-10">
 <div class="max-w-6xl mx-auto animate-fade-in-up">
 @yield('content')
 </div>
 </main>
</body>
</html>
