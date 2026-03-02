<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <meta name="csrf-token" content="{{ csrf_token() }}">
 <meta name="description" content="نظام تلاوة - مركز تحفيظ القرآن">
 <title>@yield('title', 'Tilawa - تحفيظ القرآن')</title>
 <link rel="preconnect" href="https://fonts.googleapis.com">
 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 {{-- خطوط عربية إسلامية جميلة --}}
 <link href="https://fonts.googleapis.com/css2?family=Amiri+Quran&family=Amiri:wght@400;700&family=Noto+Kufi+Arabic:wght@400;500;600;700&family=Tajawal:wght@300;400;500;600;700;800&family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
 {{-- خط المصحف العثماني من مجمع الملك فهد --}}
 <link href="https://fonts.googleapis.com/css2?family=Scheherazade+New:wght@400;700&display=swap" rel="stylesheet">
 {{-- PWA --}}
 <link rel="manifest" href="/manifest.json">
 <meta name="theme-color" content="#c9a84c">
 <meta name="mobile-web-app-capable" content="yes">
 <meta name="apple-mobile-web-app-capable" content="yes">
 <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
 <meta name="apple-mobile-web-app-title" content="تلاوة">
 <link rel="apple-touch-icon" sizes="152x152" href="/icons/icon-152x152.png">
 <link rel="apple-touch-icon" sizes="144x144" href="/icons/icon-144x144.png">
 <link rel="apple-touch-icon" sizes="128x128" href="/icons/icon-128x128.png">
 <link rel="apple-touch-icon" sizes="192x192" href="/icons/icon-192x192.png">
 <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
 <link rel="icon" type="image/svg+xml" href="/icons/icon.svg">
 <link rel="icon" type="image/png" sizes="192x192" href="/icons/icon-192x192.png">
 <link rel="icon" type="image/png" sizes="128x128" href="/icons/icon-128x128.png">
 @vite(['resources/css/app.css', 'resources/js/app.js'])
 <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
 @stack('styles')
 <style>
 [x-cloak] { display: none !important; }
 </style>
 <script>
 // تسجيل Service Worker
 if ('serviceWorker' in navigator) {
     window.addEventListener('load', () => {
         navigator.serviceWorker.register('/sw.js')
             .catch(err => console.warn('SW:', err));
     });
 }
 </script>
</head>
<body class="bg-gray-50 text-slate-800 min-h-screen antialiased selection:bg-primary-500/20 selection:text-primary-900">
 @php
 $hideNav = request()->routeIs('login') || request()->routeIs('parent.show') || !auth()->check();
 @endphp

 <div class="min-h-screen flex">
  <!-- Desktop Sidebar -->
  @unless($hideNav)
  <aside id="desktopSidebar" class="w-[19rem] hidden lg:flex flex-col bg-[radial-gradient(circle_at_top,_rgba(59,130,246,0.14),_transparent_55%)] bg-gradient-to-b from-white via-slate-50 to-slate-100/90 backdrop-blur-xl border-l border-slate-200/70 shadow-[0_25px_80px_-40px_rgba(15,23,42,0.45)] sticky top-0 h-screen overflow-y-auto z-30">
  <!-- Brand -->
  <div class="px-6 py-6 border-b border-slate-100/80">
  <div class="flex items-center justify-between">
  <div class="flex items-center gap-3">
  <div class="h-11 w-11 rounded-2xl shadow-lg shadow-primary-500/30 ring-1 ring-white/60 sidebar-logo flex-shrink-0 bg-white flex items-center justify-center overflow-hidden" style="padding:2px;">
  <img src="/images/logo.png" alt="Masar Soft" class="w-full h-full" style="object-fit:contain;"
       onerror="this.parentElement.innerHTML='<span style=\'display:flex;align-items:center;justify-content:center;width:100%;height:100%;background:linear-gradient(135deg,#4f46e5,#7c3aed);color:white;font-weight:800;font-size:1.2rem;border-radius:inherit;\'>ت</span>'">
  </div>
  <div class="sidebar-text overflow-hidden transition-all duration-300 whitespace-nowrap">
  <div class="font-extrabold text-xl text-slate-900 tracking-tight">Tilawa</div>
  <div class="text-[11px] font-semibold text-slate-500">مركز تحفيظ القرآن</div>
  </div>
  </div>
  <div class="sidebar-text hidden lg:flex items-center gap-2 rounded-full border border-slate-200/70 bg-white/70 px-3 py-1 text-[11px] font-semibold text-slate-500 shadow-sm">
  <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.6)]"></span>
  نشط
  </div>
  </div>
  </div>

 <!-- Sidebar Navigation -->
 <div class="flex-1 overflow-y-auto py-6 px-4">
 <x-sidebar />
 </div>

  <!-- Enhanced Logout Button -->
  @auth
  <div class="p-4 border-t border-slate-100 bg-gradient-to-t from-slate-50/60 to-transparent">
  <form action="{{ route('logout') }}" method="POST">
  @csrf
  <button type="submit" class="group w-full inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-semibold rounded-xl text-red-600 hover:bg-gradient-to-r hover:from-red-50 hover:to-red-100/80 hover:text-red-700 hover:shadow-lg hover:shadow-red-500/20 transition-all duration-300 cursor-pointer active:scale-[0.98]">
  <svg class="w-5 h-5 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
  </svg>
  <span>تسجيل الخروج</span>
  </button>
  </form>
  </div>
  @endauth
 </aside>
 @endunless

 <!-- Main Content Area -->
 <main class="flex-1 min-h-screen flex flex-col bg-gradient-to-b from-slate-50 via-white to-slate-100/70">
 <!-- Mobile Header -->
 @unless($hideNav)
 <x-header />
 @endunless

 <!-- Mobile Bottom Navigation -->
 @unless($hideNav)
 <x-mobile-nav />
 @endunless

 <!-- Content Area -->
 <div class="px-4 py-6 lg:px-8 lg:py-8 w-full flex-1 pb-24 lg:pb-8 max-w-7xl mx-auto">
 <!-- Success Alert -->
 @if (session('success'))
 <x-alert variant="success" dismissible class="mb-6 shadow-sm">
 {{ session('success') }}
 </x-alert>
 @endif

 <!-- Error Alert -->
 @if ($errors->any())
 <x-alert variant="error" dismissible class="mb-6 shadow-sm">
 <div class="alert-title font-bold mb-1">خطأ في النموذج</div>
 <ul class="list-disc pr-4 space-y-1 text-sm">
 @foreach ($errors->all() as $error)
 <li>{{ $error }}</li>
 @endforeach
 </ul>
 </x-alert>
 @endif

 <!-- Warning Alert -->
 @if (session('warning'))
 <x-alert variant="warning" dismissible class="mb-6 shadow-sm">
 {{ session('warning') }}
 </x-alert>
 @endif

 <!-- Info Alert -->
 @if (session('info'))
 <x-alert variant="info" dismissible class="mb-6 shadow-sm">
 {{ session('info') }}
 </x-alert>
 @endif

 <!-- Page Content -->
 <div class="animate-fade-in-up">
 @yield('content')
 </div>

 <!-- Footer -->
 <footer class="mt-8 pb-6 text-center border-t border-slate-200/60 pt-5">
  <p class="text-xs text-slate-400" style="font-family:'Tajawal',sans-serif;">
   صُمِّم بالكامل بواسطة
   <a href="https://masarsoft.io" target="_blank" class="text-primary-500 hover:text-primary-700 font-semibold transition-colors">masarsoft.io</a>
   &nbsp;•&nbsp; نسألكم الدعاء 🤲
  </p>
 </footer>
 </div>
 </main>
 </div>

 <!-- Safe area padding for iOS -->
 <style>
 ::-webkit-scrollbar { width: 6px; height: 6px; }
 ::-webkit-scrollbar-track { background: transparent; }
 ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
 ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
 
 @supports (padding: max(0px)) {
 .safe-area-bottom {
 padding-bottom: max(16px, env(safe-area-inset-bottom));
 }
 }
 
 /* Smooth Fade In Animation */
 @keyframes fadeInUp {
 from { opacity: 0; transform: translateY(10px); }
 to { opacity: 1; transform: translateY(0); }
 }
 .animate-fade-in-up {
 animation: fadeInUp 0.4s ease-out forwards;
 }
 </style>

 <!-- Modals Stack -->
 @stack('modals')

  <script>
  // Smooth scroll for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
  e.preventDefault();
  const target = document.querySelector(this.getAttribute('href'));
  if (target) {
  target.scrollIntoView({
  behavior: 'smooth',
  block: 'start'
  });
  }
  });
  });

  // Sidebar collapse removed per request
  </script>

 @stack('scripts')
</body>
</html>
