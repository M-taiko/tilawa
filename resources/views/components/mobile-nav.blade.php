 {{-- Enhanced Mobile Navigation --}}
 <div id="mobileNav" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden opacity-0 transition-all duration-300 lg:hidden" style="-webkit-backdrop-filter: blur(8px);">
  <div class="fixed right-0 top-0 h-full w-80 bg-[radial-gradient(circle_at_top,_rgba(59,130,246,0.14),_transparent_55%)] bg-gradient-to-b from-white via-slate-50 to-slate-100/90 shadow-[0_25px_80px_-40px_rgba(15,23,42,0.45)] overflow-y-auto transform transition-all duration-300 ease-out translate-x-full will-change-transform" id="mobileMenu">
  <!-- Brand -->
  <div class="p-6 border-b border-slate-100 flex justify-between items-center">
  <div class="flex items-center gap-3">
  <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-primary-600 to-primary-700 text-white flex items-center justify-center font-extrabold text-xl shadow-lg shadow-primary-500/30 ring-1 ring-white/60">
  ت
  </div>
  <div>
  <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">تلاوة</h2>
  <p class="text-[11px] font-semibold text-slate-500">مركز تحفيظ القرآن</p>
  </div>
  </div>
  <button id="mobileNavClose" class="p-2 -mr-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition-all duration-200 active:scale-95" aria-label="إغلاق القائمة">
  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
  </svg>
  </button>
  </div>

 <!-- Navigation Links -->
 <div class="p-4">
 <x-sidebar />
 </div>

  <!-- Enhanced Logout -->
  @auth
  <div class="p-4 border-t border-slate-100 bg-gradient-to-t from-slate-50/60 to-transparent mt-auto">
  <form method="POST" action="{{ route('logout') }}">
  @csrf
  <button type="submit" class="group flex items-center justify-center gap-2 px-4 py-3.5 rounded-xl text-red-600 bg-gradient-to-r hover:from-red-50 hover:to-red-100/80 hover:text-red-700 hover:shadow-lg hover:shadow-red-500/20 font-medium w-full transition-all duration-300 cursor-pointer active:scale-[0.98]">
  <svg class="w-5 h-5 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
  </svg>
  <span>تسجيل الخروج</span>
  </button>
  </form>
  </div>
  @endauth
 </div>
</div>

 <script>
  const mobileNav = document.getElementById('mobileNav');
  const mobileMenu = document.getElementById('mobileMenu');
  const toggleBtn = document.getElementById('mobileSidebarToggle');
  const closeBtn = document.getElementById('mobileNavClose');

  let touchStartX = 0;
  let touchEndX = 0;
  let isDragging = false;

  function openNav() {
  mobileNav.classList.remove('hidden');
  setTimeout(() => {
  mobileNav.classList.remove('opacity-0');
  mobileMenu.classList.remove('translate-x-full');
  }, 10);
  document.body.style.overflow = 'hidden';
  }

  function closeNav() {
  mobileNav.classList.add('opacity-0');
  mobileMenu.classList.add('translate-x-full');
  setTimeout(() => {
  mobileNav.classList.add('hidden');
  document.body.style.overflow = '';
  }, 300);
  }

  toggleBtn?.addEventListener('click', openNav);
  closeBtn?.addEventListener('click', closeNav);
  mobileMenu?.querySelectorAll('a').forEach(link => {
  link.addEventListener('click', closeNav);
  });

  mobileNav?.addEventListener('click', function(e) {
  if (e.target === this) {
  closeNav();
  }
  });

  // Enhanced swipe gesture support
  mobileMenu?.addEventListener('touchstart', function(e) {
  touchStartX = e.changedTouches[0].screenX;
  isDragging = true;
  }, { passive: true });

  mobileMenu?.addEventListener('touchmove', function(e) {
  if (!isDragging) return;
  
  const touchX = e.changedTouches[0].screenX;
  const diff = touchX - touchStartX;
  
  if (diff < 0) {
  const translateX = Math.max(diff, -320);
  mobileMenu.style.transform = `translateX(${translateX}px)`;
  mobileNav.style.opacity = 1 - (Math.abs(diff) / 320);
  }
  }, { passive: true });

  mobileMenu?.addEventListener('touchend', function(e) {
  if (!isDragging) return;
  isDragging = false;
  
  touchEndX = e.changedTouches[0].screenX;
  const diff = touchEndX - touchStartX;
  
  mobileMenu.style.transform = '';
  mobileNav.style.opacity = '';
  
  if (diff < -100) {
  closeNav();
  }
  }, { passive: true });

  // Close on escape key
  document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape' && !mobileNav.classList.contains('hidden')) {
  closeNav();
  }
  });
 </script>
