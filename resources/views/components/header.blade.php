{{-- Header Component --}}
<header class="bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-20 px-4 py-3 lg:px-8 mb-6 lg:mb-8 shadow-sm">
 <div class="flex items-center justify-between max-w-7xl mx-auto">
 <div class="flex items-center gap-4">
 <button id="mobileSidebarToggle" class="lg:hidden p-2 rounded-xl text-slate-500 hover:bg-slate-100 transition-colors">
 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
 </svg>
 </button>
 
 <div class="flex flex-col">
 <h1 class="text-xl lg:text-2xl font-bold text-slate-800 tracking-tight">{{ $title ?? 'لوحة التحكم' }}</h1>
 <p class="hidden lg:block text-xs text-slate-500 font-medium">{{ now()->format('Y-m-d') }} - مرحبًا بك في تلاوة</p>
 </div>
 </div>

 <div class="flex items-center gap-3 lg:gap-6">
 <!-- Language Toggle -->
 <div class="flex items-center gap-1 bg-slate-100 rounded-lg p-1">
 <form method="POST" action="{{ route('locale.switch') }}" class="inline">
 @csrf
 <input type="hidden" name="locale" value="ar">
 <button type="submit" class="px-2.5 py-1.5 rounded-md text-xs font-semibold transition-colors {{ app()->getLocale() === 'ar' ? 'bg-white text-primary-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
 العربية
 </button>
 </form>
 <form method="POST" action="{{ route('locale.switch') }}" class="inline">
 @csrf
 <input type="hidden" name="locale" value="en">
 <button type="submit" class="px-2.5 py-1.5 rounded-md text-xs font-semibold transition-colors {{ app()->getLocale() === 'en' ? 'bg-white text-primary-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
 EN
 </button>
 </form>
 </div>

 <!-- User Profile -->
 <div class="flex items-center gap-3 pl-2 lg:pl-6 border-l border-slate-200">
 @auth
 <div class="hidden md:block text-left group cursor-pointer">
 <div class="text-sm font-bold text-slate-800 group-hover:text-primary-600 transition-colors">{{ auth()->user()->name }}</div>
 <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">{{ auth()->user()->email }}</div>
 </div>
 <x-avatar class="ring-2 ring-white shadow-md cursor-pointer hover:ring-primary-100 transition-all">{{ substr(auth()->user()->name, 0, 1) }}</x-avatar>
 @else
 <a href="{{ route('login') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors">{{ app()->getLocale() === 'ar' ? 'تسجيل الدخول' : 'Sign In' }}</a>
 @endauth
 </div>
 </div>
 </div>
</header>
