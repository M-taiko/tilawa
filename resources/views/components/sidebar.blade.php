{{-- Sidebar Navigation Links --}}
@php
 $currentRoute = request()->route()->getName();
 $tenantRole = auth()->user()->tenants()
 ->where('tenants.id', session('current_tenant_id'))
 ->first()?->pivot?->role;

 $isActive = function($routePattern) use ($currentRoute) {
 return str_starts_with($currentRoute, $routePattern);
 };

 $linkBase = "nav-item group flex items-center gap-4 rounded-2xl px-4 py-3.5 text-[15px] transition-all duration-300 border border-white/10 bg-white/30 hover:bg-white/50 backdrop-blur-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-400/70 active:scale-[0.98]";
 $activeClass = "!bg-gradient-to-r from-primary-500/20 via-primary-400/10 to-transparent !text-primary-700 font-semibold shadow-lg shadow-primary-500/20 !border-primary-400/40 ring-1 ring-primary-300/30";
 $inactiveClass = "text-slate-700 hover:text-slate-900 hover:shadow-md hover:border-white/30";
 $iconClass = "icon-wrapper flex h-10 w-10 items-center justify-center rounded-xl transition-all duration-300 bg-gradient-to-br from-white/60 to-white/40 shadow-sm";
@endphp

<nav class="sidebar-nav flex flex-col gap-3" data-sidebar>
 {{-- Search Bar --}}
 <div class="sidebar-search-container px-4 pt-2 pb-4 mb-2">
 <div class="relative group">
 <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
 <svg class="w-4 h-4 text-slate-400 group-focus-within:text-primary-500
 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
 d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
 </svg>
 </div>
 <input type="text" id="sidebarSearch" placeholder="بحث في القائمة... (Ctrl+K)"
 class="w-full py-3 pr-10 pl-4 rounded-xl border border-white/30
 bg-white/50 backdrop-blur-md text-sm text-slate-800
 placeholder:text-slate-400 focus:outline-none
 focus:ring-2 focus:ring-primary-400/50 focus:bg-white/70
 shadow-sm hover:shadow-md transition-all" />
 </div>
 <div id="searchResultsCount" class="hidden mt-2 text-xs text-slate-600 font-medium"></div>
 </div>
 <div class="rounded-2xl border border-white/20 bg-gradient-to-br from-white/60 via-white/50 to-white/40 backdrop-blur-md px-5 py-5 shadow-lg mb-4">
 <div class="flex items-center gap-3">
 <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-primary-500 via-primary-600 to-primary-700 text-white flex items-center justify-center font-extrabold text-2xl shadow-xl shadow-primary-500/30 ring-2 ring-white/40">
 {{ substr(auth()->user()->name, 0, 1) }}
 </div>
 <div class="flex-1 min-w-0">
 <div class="text-base font-bold text-slate-900 truncate">{{ auth()->user()->name }}</div>
 <div class="text-xs font-medium text-slate-600 truncate">{{ auth()->user()->email }}</div>
 </div>
 </div>
 <div class="mt-4 flex items-center gap-2">
 <span class="inline-flex items-center gap-2 rounded-full bg-emerald-500/10 px-3 py-1.5 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-500/20 shadow-sm">
 <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
 نشط
 </span>
 <span class="rounded-full border border-primary-200/50 bg-primary-50/60 px-3 py-1.5 text-xs font-semibold text-primary-700 shadow-sm backdrop-blur-sm">لوحة التحكم</span>
 </div>
 </div>

 @if(auth()->user()->isSaasAdmin())
 <div class="sidebar-section px-4" data-section="saas-admin">
 <div class="mb-3 pb-2 border-b border-gradient-to-r from-slate-200/50 to-transparent">
 <h3 class="text-xs font-bold text-slate-600 uppercase tracking-wider">إدارة النظام</h3>
 </div>
 <div class="nav-section flex flex-col gap-2">
 <a href="{{ route('saas.tenants.index') }}" data-tooltip="المراكز" class="{{ $linkBase }} {{ $isActive('saas.tenants') ? $activeClass : $inactiveClass }}">
 <span class="{{ $iconClass }} {{ $isActive('saas.tenants') ? '!bg-gradient-to-br !from-primary-500 !to-primary-600 !text-white !shadow-lg !shadow-primary-500/30' : 'text-slate-600 group-hover:text-primary-600 group-hover:scale-110' }}">
 <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $isActive('saas.tenants') ? '2' : '1.5' }}" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
 </svg>
 </span>
 <span class="tracking-tight sidebar-text">المراكز</span>
 </a>
 </div>
 </div>
 @elseif($tenantRole === 'tenant_admin')
 <div class="sidebar-section px-4" data-section="admin-general">
 <div class="mb-3 pb-2 border-b border-slate-200/50">
 <h3 class="text-xs font-bold text-slate-600 uppercase tracking-wider">الإدارة العامة</h3>
 </div>
 <div class="nav-section flex flex-col gap-2">
 <a href="{{ route('admin.dashboard') }}" data-tooltip="لوحة التحكم" class="{{ $linkBase }} {{ $isActive('admin.dashboard') ? $activeClass : $inactiveClass }}">
 <span class="{{ $iconClass }} {{ $isActive('admin.dashboard') ? '!bg-gradient-to-br !from-primary-500 !to-primary-600 !text-white !shadow-lg !shadow-primary-500/30' : 'text-slate-600 group-hover:text-primary-600 group-hover:scale-110' }}">
 <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $isActive('admin.dashboard') ? '2' : '1.5' }}" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
 </svg>
 </span>
 <span class="tracking-tight sidebar-text">لوحة التحكم</span>
 </a>

 <a href="{{ route('admin.students.index') }}" data-tooltip="الطلاب" class="{{ $linkBase }} {{ $isActive('admin.students') ? $activeClass : $inactiveClass }}">
 <span class="{{ $iconClass }} {{ $isActive('admin.students') ? '!bg-gradient-to-br !from-primary-500 !to-primary-600 !text-white !shadow-lg !shadow-primary-500/30' : 'text-slate-600 group-hover:text-primary-600 group-hover:scale-110' }}">
 <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $isActive('admin.students') ? '2' : '1.5' }}" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
 </svg>
 </span>
 <span class="tracking-tight sidebar-text">الطلاب</span>
 </a>

 <a href="{{ route('admin.teachers.index') }}" data-tooltip="المعلمون" class="{{ $linkBase }} {{ $isActive('admin.teachers.index') || $isActive('admin.teachers.create') || $isActive('admin.teachers.edit') ? $activeClass : $inactiveClass }}">
 <span class="{{ $iconClass }} {{ $isActive('admin.teachers') ? '!bg-gradient-to-br !from-primary-500 !to-primary-600 !text-white !shadow-lg !shadow-primary-500/30' : 'text-slate-600 group-hover:text-primary-600 group-hover:scale-110' }}">
 <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $isActive('admin.teachers') ? '2' : '1.5' }}" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
 </svg>
 </span>
 <span class="tracking-tight sidebar-text">المعلمون</span>
 </a>

 <a href="{{ route('admin.teachers.workload') }}" data-tooltip="أعباء العمل" class="{{ $linkBase }} {{ $isActive('admin.teachers.workload') ? $activeClass : $inactiveClass }}">
 <span class="{{ $iconClass }} {{ $isActive('admin.teachers.workload') ? '!bg-gradient-to-br !from-primary-500 !to-primary-600 !text-white !shadow-lg !shadow-primary-500/30' : 'text-slate-600 group-hover:text-primary-600 group-hover:scale-110' }}">
 <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $isActive('admin.teachers.workload') ? '2' : '1.5' }}" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
 </svg>
 </span>
 <span class="tracking-tight sidebar-text">أعباء العمل</span>
 </a>

 <a href="{{ route('admin.classes.index') }}" data-tooltip="الحلقات" class="{{ $linkBase }} {{ $isActive('admin.classes') ? $activeClass : $inactiveClass }}">
 <span class="{{ $iconClass }} {{ $isActive('admin.classes') ? '!bg-gradient-to-br !from-primary-500 !to-primary-600 !text-white !shadow-lg !shadow-primary-500/30' : 'text-slate-600 group-hover:text-primary-600 group-hover:scale-110' }}">
 <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $isActive('admin.classes') ? '2' : '1.5' }}" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
 </svg>
 </span>
 <span class="tracking-tight sidebar-text">الحلقات</span>
 </a>

 <a href="{{ route('admin.schedules.calendar') }}" data-tooltip="الجدول الأسبوعي" class="{{ $linkBase }} {{ $isActive('admin.schedules') ? $activeClass : $inactiveClass }}">
 <span class="{{ $iconClass }} {{ $isActive('admin.schedules') ? '!bg-gradient-to-br !from-primary-500 !to-primary-600 !text-white !shadow-lg !shadow-primary-500/30' : 'text-slate-600 group-hover:text-primary-600 group-hover:scale-110' }}">
 <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $isActive('admin.schedules') ? '2' : '1.5' }}" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
 </svg>
 </span>
 <span class="tracking-tight sidebar-text">الجدول الأسبوعي</span>
 </a>
 </div>

 <div class="sidebar-section px-4 mt-6 pt-6 border-t border-slate-200/60" data-section="admin-educational">
 <div class="mb-3 pb-2 border-b border-slate-200/50">
 <h3 class="text-xs font-bold text-slate-600 uppercase tracking-wider">التعليمي & التقارير</h3>
 </div>
 <div class="nav-section flex flex-col gap-2">
 <a href="{{ route('admin.foundation-skills.index') }}" data-tooltip="مهارات التأسيس" class="{{ $linkBase }} {{ $isActive('admin.foundation-skills') ? $activeClass : $inactiveClass }}">
 <span class="{{ $iconClass }} {{ $isActive('admin.foundation-skills') ? '!bg-gradient-to-br !from-primary-500 !to-primary-600 !text-white !shadow-lg !shadow-primary-500/30' : 'text-slate-600 group-hover:text-primary-600 group-hover:scale-110' }}">
 <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $isActive('admin.foundation-skills') ? '2' : '1.5' }}" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </span>
 <span class="tracking-tight sidebar-text">مهارات التأسيس</span>
 </a>

 <a href="{{ route('admin.reports.index') }}" data-tooltip="التقارير" class="{{ $linkBase }} {{ $isActive('admin.reports') ? $activeClass : $inactiveClass }}">
 <span class="{{ $iconClass }} {{ $isActive('admin.reports') ? '!bg-gradient-to-br !from-primary-500 !to-primary-600 !text-white !shadow-lg !shadow-primary-500/30' : 'text-slate-600 group-hover:text-primary-600 group-hover:scale-110' }}">
 <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $isActive('admin.reports') ? '2' : '1.5' }}" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
 </svg>
 </span>
 <span class="tracking-tight sidebar-text">التقارير</span>
 </a>
 </div>
 </div>

 <div class="sidebar-section px-4 mt-6 pt-6 border-t border-slate-200/60" data-section="admin-system">
 <div class="mb-3 pb-2 border-b border-slate-200/50">
 <h3 class="text-xs font-bold text-slate-600 uppercase tracking-wider">إدارة النظام</h3>
 </div>
 <div class="nav-section flex flex-col gap-2">
 <a href="{{ route('admin.announcements.index') }}" data-tooltip="الإعلانات" class="{{ $linkBase }} {{ $isActive('admin.announcements') ? $activeClass : $inactiveClass }}">
 <span class="{{ $iconClass }} {{ $isActive('admin.announcements') ? '!bg-gradient-to-br !from-primary-500 !to-primary-600 !text-white !shadow-lg !shadow-primary-500/30' : 'text-slate-600 group-hover:text-primary-600 group-hover:scale-110' }}">
 <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $isActive('admin.announcements') ? '2' : '1.5' }}" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
 </svg>
 </span>
 <span class="tracking-tight sidebar-text">الإعلانات</span>
 </a>

 <a href="{{ route('admin.holidays.index') }}" data-tooltip="الإجازات والعطلات" class="{{ $linkBase }} {{ $isActive('admin.holidays.index') || $isActive('admin.holidays.create') || $isActive('admin.holidays.edit') ? $activeClass : $inactiveClass }}">
 <span class="{{ $iconClass }} {{ $isActive('admin.holidays') ? '!bg-gradient-to-br !from-primary-500 !to-primary-600 !text-white !shadow-lg !shadow-primary-500/30' : 'text-slate-600 group-hover:text-primary-600 group-hover:scale-110' }}">
 <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $isActive('admin.holidays') ? '2' : '1.5' }}" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
 </svg>
 </span>
 <span class="tracking-tight sidebar-text">الإجازات والعطلات</span>
 </a>

 <a href="{{ route('admin.holidays.calendar') }}" data-tooltip="تقويم الإجازات" class="{{ $linkBase }} {{ $isActive('admin.holidays.calendar') ? $activeClass : $inactiveClass }}">
 <span class="{{ $iconClass }} {{ $isActive('admin.holidays.calendar') ? '!bg-gradient-to-br !from-primary-500 !to-primary-600 !text-white !shadow-lg !shadow-primary-500/30' : 'text-slate-600 group-hover:text-primary-600 group-hover:scale-110' }}">
 <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $isActive('admin.holidays.calendar') ? '2' : '1.5' }}" d="M9 3v2m6-2v2M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
 </svg>
 </span>
 <span class="tracking-tight sidebar-text">تقويم الإجازات</span>
 </a>

 <a href="{{ route('admin.activity-logs.index') }}" data-tooltip="سجل النشاطات" class="{{ $linkBase }} {{ $isActive('admin.activity-logs') ? $activeClass : $inactiveClass }}">
 <span class="{{ $iconClass }} {{ $isActive('admin.activity-logs') ? '!bg-gradient-to-br !from-primary-500 !to-primary-600 !text-white !shadow-lg !shadow-primary-500/30' : 'text-slate-600 group-hover:text-primary-600 group-hover:scale-110' }}">
 <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $isActive('admin.activity-logs') ? '2' : '1.5' }}" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
 </svg>
 </span>
 <span class="tracking-tight sidebar-text">سجل النشاطات</span>
 </a>
 </div>
 </div>
 @elseif($tenantRole === 'teacher')
 <div class="sidebar-section px-4" data-section="teacher-main">
 <div class="mb-3 pb-2 border-b border-slate-200/50">
 <h3 class="text-xs font-bold text-slate-600 uppercase tracking-wider">المعلم</h3>
 </div>
 <div class="nav-section flex flex-col gap-2">
 <a href="{{ route('teacher.dashboard') }}" data-tooltip="لوحة التحكم" class="{{ $linkBase }} {{ $isActive('teacher.dashboard') ? $activeClass : $inactiveClass }}">
 <span class="{{ $iconClass }} {{ $isActive('teacher.dashboard') ? '!bg-gradient-to-br !from-primary-500 !to-primary-600 !text-white !shadow-lg !shadow-primary-500/30' : 'text-slate-600 group-hover:text-primary-600 group-hover:scale-110' }}">
 <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $isActive('teacher.dashboard') ? '2' : '1.5' }}" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
 </svg>
 </span>
 <span class="tracking-tight sidebar-text">لوحة التحكم</span>
 </a>

 <a href="{{ route('teacher.students.index') }}" data-tooltip="طلابي" class="{{ $linkBase }} {{ $isActive('teacher.students') ? $activeClass : $inactiveClass }}">
 <span class="{{ $iconClass }} {{ $isActive('teacher.students') ? '!bg-gradient-to-br !from-primary-500 !to-primary-600 !text-white !shadow-lg !shadow-primary-500/30' : 'text-slate-600 group-hover:text-primary-600 group-hover:scale-110' }}">
 <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $isActive('teacher.students') ? '2' : '1.5' }}" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
 </svg>
 </span>
 <span class="tracking-tight sidebar-text">طلابي</span>
 </a>

 <a href="{{ route('teacher.sessions.index') }}" data-tooltip="الجلسات" class="{{ $linkBase }} {{ $isActive('teacher.sessions') ? $activeClass : $inactiveClass }}">
 <span class="{{ $iconClass }} {{ $isActive('teacher.sessions') ? '!bg-gradient-to-br !from-primary-500 !to-primary-600 !text-white !shadow-lg !shadow-primary-500/30' : 'text-slate-600 group-hover:text-primary-600 group-hover:scale-110' }}">
 <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $isActive('teacher.sessions') ? '2' : '1.5' }}" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
 </svg>
 </span>
 <span class="tracking-tight sidebar-text">الجلسات</span>
 </a>

 <a href="{{ route('teacher.schedule') }}" data-tooltip="جدولي" class="{{ $linkBase }} {{ $isActive('teacher.schedule') ? $activeClass : $inactiveClass }}">
 <span class="{{ $iconClass }} {{ $isActive('teacher.schedule') ? '!bg-gradient-to-br !from-primary-500 !to-primary-600 !text-white !shadow-lg !shadow-primary-500/30' : 'text-slate-600 group-hover:text-primary-600 group-hover:scale-110' }}">
 <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $isActive('teacher.schedule') ? '2' : '1.5' }}" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
 </svg>
 </span>
 <span class="tracking-tight sidebar-text">جدولي</span>
 </a>

 <a href="{{ route('teacher.reports.index') }}" data-tooltip="تقاريري" class="{{ $linkBase }} {{ $isActive('teacher.reports') ? $activeClass : $inactiveClass }}">
 <span class="{{ $iconClass }} {{ $isActive('teacher.reports') ? '!bg-gradient-to-br !from-primary-500 !to-primary-600 !text-white !shadow-lg !shadow-primary-500/30' : 'text-slate-600 group-hover:text-primary-600 group-hover:scale-110' }}">
 <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $isActive('teacher.reports') ? '2' : '1.5' }}" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
 </svg>
 </span>
 <span class="tracking-tight sidebar-text">تقاريري</span>
 </a>
 </div>
 </div>
 @endif

 <div class="sidebar-section px-4 mt-6 pt-6 border-t border-slate-200/60" data-section="profile">
 <div class="mb-3 pb-2 border-b border-slate-200/50">
 <h3 class="text-xs font-bold text-slate-600 uppercase tracking-wider">حسابي</h3>
 </div>
 <div class="nav-section flex flex-col gap-2">
 <a href="{{ route('profile.edit') }}" data-tooltip="الملف الشخصي" class="{{ $linkBase }} {{ $isActive('profile') ? $activeClass : $inactiveClass }}">
 <span class="{{ $iconClass }} {{ $isActive('profile') ? '!bg-gradient-to-br !from-primary-500 !to-primary-600 !text-white !shadow-lg !shadow-primary-500/30' : 'text-slate-600 group-hover:text-primary-600 group-hover:scale-110' }}">
 <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="{{ $isActive('profile') ? '2' : '1.5' }}" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
 </svg>
 </span>
 <span class="tracking-tight sidebar-text">الملف الشخصي</span>
 </a>
 </div>
 </div>
</nav>
