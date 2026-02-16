@extends($isPublic ? 'layouts.public' : 'layouts.app')

@section('title', 'تقدم الطالب - ' . $student->name)

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Admin Header with Back Button --}}
    @if(!$isPublic)
    <div class="mb-6">
        <x-button
            :href="route('admin.students.index')"
            variant="ghost"
            size="sm"
            class="mb-4 !px-0"
        >
            <svg class="w-4 h-4 rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l-5 5 5 5"/>
            </svg>
            رجوع للطلاب
        </x-button>
    </div>
    @endif

    {{-- Redesigned Hero Header --}}
    <div class="relative bg-gradient-to-br from-emerald-600 via-teal-700 to-cyan-900 rounded-2xl shadow-2xl overflow-hidden mb-8 text-white">
        {{-- Animated Background Pattern --}}
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_50%,rgba(255,255,255,0.1)_0%,transparent_50%)]"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_70%_80%,rgba(255,255,255,0.08)_0%,transparent_50%)]"></div>
        </div>

        {{-- Decorative Islamic Pattern --}}
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: url('data:image/svg+xml,%3Csvg width=\'80\' height=\'80\' viewBox=\'0 0 80 80\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M0 0h40v40H0V0zm40 40h40v40H40V40zm0-40h2l-2 2V0zm0 4l4-4h2l-6 6V4zm0 4l8-8h2L40 10V8zm0 4L52 0h2L40 14v-2zm0 4L56 0h2L40 18v-2zm0 4L60 0h2L40 22v-2zm0 4L64 0h2L40 26v-2zm0 4L68 0h2L40 30v-2zm0 4L72 0h2L40 34v-2zm0 4L76 0h2L40 38v-2zm0 4L80 0v2L42 40h-2zm4 0L80 4v2L46 40h-2zm4 0L80 8v2L50 40h-2zm4 0l28-28v2L54 40h-2zm4 0l24-24v2L58 40h-2zm4 0l20-20v2L62 40h-2zm4 0l16-16v2L66 40h-2zm4 0l12-12v2L70 40h-2zm4 0l8-8v2l-6 6h-2zm4 0l4-4v2l-2 2h-2z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

        <div class="relative z-10 px-6 py-8 lg:px-10">
            <div class="flex flex-col lg:flex-row items-center gap-8">
                {{-- Student Info Section --}}
                <div class="flex-1 text-center lg:text-right space-y-5 w-full">
                    {{-- Compact Page Badge --}}
                    <div class="inline-flex items-center gap-2 bg-emerald-500/20 backdrop-blur-sm px-4 py-2 rounded-full border border-emerald-400/40">
                        <div class="w-2 h-2 bg-emerald-300 rounded-full animate-pulse shadow-lg shadow-emerald-400/50"></div>
                        <span class="text-xs font-bold uppercase tracking-wide text-emerald-100">{{ $isPublic ? 'ملف الطالب' : 'خريطة التقدم' }}</span>
                    </div>

                    {{-- Student Name --}}
                    <div class="space-y-2">
                        <h1 class="text-3xl md:text-4xl lg:text-5xl font-black drop-shadow-2xl leading-tight text-white">{{ $student->name }}</h1>
                        <div class="flex items-center justify-center lg:justify-start gap-2">
                            <div class="h-1 w-20 bg-gradient-to-r from-emerald-300 via-emerald-400 to-transparent rounded-full shadow-lg shadow-emerald-400/50"></div>
                            <div class="h-0.5 w-10 bg-gradient-to-r from-emerald-300/60 to-transparent rounded-full"></div>
                        </div>
                    </div>

                    {{-- Compact Info Pills --}}
                    <div class="flex flex-wrap justify-center lg:justify-start items-center gap-2">
                        <div class="flex items-center gap-1.5 bg-white/15 backdrop-blur-sm px-3 py-1.5 rounded-lg border border-white/30 hover:bg-white/20 hover:border-white/40 transition-all">
                            <svg class="w-3.5 h-3.5 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span class="text-xs font-bold text-white">{{ $student->group === 'kids' ? 'أطفال' : ($student->group === 'men' ? 'رجال' : 'نساء') }}</span>
                        </div>

                        <div class="flex items-center gap-1.5 bg-white/15 backdrop-blur-sm px-3 py-1.5 rounded-lg border border-white/30 hover:bg-white/20 hover:border-white/40 transition-all">
                            <svg class="w-3.5 h-3.5 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <span class="text-xs font-bold text-white">{{ $student->track === 'memorization' ? 'الحفظ' : 'التأسيس' }}</span>
                        </div>

                        @if($student->class)
                        <div class="flex items-center gap-1.5 bg-white/15 backdrop-blur-sm px-3 py-1.5 rounded-lg border border-white/30 hover:bg-white/20 hover:border-white/40 transition-all">
                            <svg class="w-3.5 h-3.5 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span class="text-xs font-bold text-white">{{ $student->class->name }}</span>
                        </div>
                        @endif
                    </div>

                    {{-- Admin Actions --}}
                    @if(!$isPublic)
                    <div class="flex flex-wrap justify-center lg:justify-start gap-2 pt-1">
                        <x-button
                            :href="route('teacher.sessions.create', ['student_id' => $student->id])"
                            variant="primary"
                            size="sm"
                            class="!bg-white !text-emerald-700 hover:!bg-emerald-50 !shadow-md !font-bold !text-xs"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>إضافة جلسة</span>
                        </x-button>
                        <x-button
                            :href="route('admin.students.edit', $student->id)"
                            variant="ghost"
                            size="sm"
                            class="!bg-white/10 !text-white hover:!bg-white/20 !border-white/30 !font-bold !text-xs backdrop-blur-sm"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <span>تعديل</span>
                        </x-button>
                    </div>
                    @endif
                </div>

                {{-- Enhanced Progress Container --}}
                <div class="flex-shrink-0">
                    <div class="relative group">
                        {{-- Layered Glow Effects --}}
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-400/40 to-teal-400/30 rounded-full blur-3xl group-hover:blur-[60px] transition-all duration-500"></div>
                        <div class="absolute inset-0 bg-emerald-300/20 rounded-full blur-2xl animate-pulse"></div>

                        {{-- Outer Ring Decoration --}}
                        <div class="relative">
                            <div class="absolute inset-0 rounded-full bg-gradient-to-br from-white/20 via-emerald-400/10 to-transparent blur-sm"></div>

                            {{-- Main Progress Container --}}
                            <div class="relative bg-gradient-to-br from-white/15 via-white/10 to-white/5 backdrop-blur-xl rounded-full p-2 border-2 border-white/40 shadow-2xl">
                                {{-- Inner Glow Ring --}}
                                <div class="absolute inset-2 rounded-full bg-gradient-to-br from-emerald-400/5 to-transparent"></div>

                                {{-- Progress SVG --}}
                                <div class="relative p-3">
                                    <svg class="w-36 h-36 md:w-40 md:h-40 transform -rotate-90">
                                        {{-- Background Track --}}
                                        <circle class="text-white/15" stroke-width="12" stroke="currentColor" fill="transparent" r="66" cx="80" cy="80" />

                                        {{-- Gradient Definitions --}}
                                        <defs>
                                            <linearGradient id="progressGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                                <stop offset="0%" style="stop-color:#6EE7B7;stop-opacity:1" />
                                                <stop offset="50%" style="stop-color:#34D399;stop-opacity:1" />
                                                <stop offset="100%" style="stop-color:#10B981;stop-opacity:1" />
                                            </linearGradient>
                                            <filter id="glow">
                                                <feGaussianBlur stdDeviation="4" result="coloredBlur"/>
                                                <feMerge>
                                                    <feMergeNode in="coloredBlur"/>
                                                    <feMergeNode in="SourceGraphic"/>
                                                </feMerge>
                                            </filter>
                                        </defs>

                                        {{-- Animated Progress Arc --}}
                                        <circle
                                            class="transition-all duration-1000 ease-out"
                                            stroke="url(#progressGradient)"
                                            stroke-width="12"
                                            stroke-dasharray="{{ 2 * 3.14 * 66 }}"
                                            stroke-dashoffset="{{ 2 * 3.14 * 66 * (1 - $statistics['progress_percent'] / 100) }}"
                                            stroke-linecap="round"
                                            fill="transparent"
                                            r="66"
                                            cx="80"
                                            cy="80"
                                            filter="url(#glow)"
                                        />
                                    </svg>

                                    {{-- Center Content with Stats --}}
                                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                                        {{-- Main Percentage --}}
                                        <div class="flex items-start">
                                            <span class="text-5xl md:text-6xl font-black text-white drop-shadow-2xl">{{ number_format($statistics['progress_percent'], 1) }}</span>
                                            <span class="text-2xl font-black text-emerald-200 mt-1">%</span>
                                        </div>

                                        {{-- Label --}}
                                        <div class="mt-1 px-3 py-0.5 bg-emerald-400/20 backdrop-blur-sm rounded-full border border-emerald-300/30">
                                            <div class="text-[10px] font-bold text-emerald-100 uppercase tracking-wider">نسبة الإنجاز</div>
                                        </div>

                                        {{-- Ayah Counter --}}
                                        <div class="mt-3 px-3 py-1.5 bg-gradient-to-r from-emerald-500/25 to-teal-500/20 backdrop-blur-md rounded-lg border border-emerald-400/40 shadow-xl">
                                            <div class="flex items-center gap-1">
                                                <svg class="w-3 h-3 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                                </svg>
                                                <span class="text-xs font-black text-white">{{ number_format($statistics['memorized_ayahs']) }}</span>
                                                <span class="text-xs text-emerald-300 font-bold">/</span>
                                                <span class="text-xs font-bold text-emerald-200">{{ number_format($statistics['total_ayahs']) }}</span>
                                            </div>
                                        </div>

                                        {{-- Surahs Completed Mini Badge --}}
                                        <div class="mt-2 px-2.5 py-1 bg-white/10 backdrop-blur-sm rounded-md border border-white/20">
                                            <div class="text-[9px] font-bold text-emerald-100">
                                                {{ $statistics['completed_surahs'] }} سورة مكتملة
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Key Statistics Grid - 6 Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <x-gradient-stat-card
            label="آيات محفوظة"
            :value="number_format($statistics['memorized_ayahs'])"
            gradient="green"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </x-gradient-stat-card>

        <x-gradient-stat-card
            label="سور مكتملة"
            :value="$statistics['completed_surahs'] . '/114'"
            gradient="purple"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
        </x-gradient-stat-card>

        <x-gradient-stat-card
            label="آيات متبقية"
            :value="number_format($statistics['remaining_ayahs'])"
            gradient="yellow"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
            </svg>
        </x-gradient-stat-card>

        <x-gradient-stat-card
            label="متوسط التقييم"
            :value="number_format($avgScore ?? 0, 1) . '/10'"
            gradient="blue"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
            </svg>
        </x-gradient-stat-card>

        <x-gradient-stat-card
            label="نسبة الحضور"
            :value="round(($presentSessions / max($totalSessions, 1)) * 100, 1) . '%'"
            gradient="purple"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </x-gradient-stat-card>

        <x-gradient-stat-card
            label="إجمالي الجلسات"
            :value="$totalSessions"
            gradient="gray"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </x-gradient-stat-card>
    </div>

    {{-- Quran Progress Map --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-8">
        {{-- Compact Header --}}
        <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-teal-50">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 text-white shadow-md">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">خريطة القرآن الكريم</h2>
                        <p class="text-xs text-gray-600 mt-0.5">114 سورة</p>
                    </div>
                </div>

                {{-- Compact Progress Stats --}}
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-1.5 px-3 py-2 bg-green-50 border border-green-300 rounded-lg">
                        <div class="flex items-center justify-center w-6 h-6 bg-green-500 rounded-md">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold text-green-900 leading-none">{{ collect($progressMap)->where('status', 'completed')->count() }}</div>
                            <div class="text-[10px] text-green-700 font-medium">مكتملة</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-1.5 px-3 py-2 bg-blue-50 border border-blue-300 rounded-lg">
                        <div class="flex items-center justify-center w-6 h-6 bg-blue-500 rounded-md">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold text-blue-900 leading-none">{{ collect($progressMap)->where('status', 'in_progress')->count() }}</div>
                            <div class="text-[10px] text-blue-700 font-medium">جارية</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-1.5 px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg">
                        <div class="flex items-center justify-center w-6 h-6 bg-gray-400 rounded-md">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold text-gray-900 leading-none">{{ collect($progressMap)->where('status', 'pending')->count() }}</div>
                            <div class="text-[10px] text-gray-600 font-medium">متبقية</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Progress Map Grid --}}
        <div class="p-5">
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 xl:grid-cols-10 gap-2">
                @foreach($progressMap as $item)
                <div class="group">
                    <div class="
                        relative rounded-lg p-2 transition-all duration-200 hover:shadow-lg hover:-translate-y-0.5 cursor-pointer
                        {{ $item['status'] === 'completed' ? 'bg-gradient-to-br from-green-50 to-emerald-100 border-2 border-green-400 hover:border-green-500' : '' }}
                        {{ $item['status'] === 'in_progress' ? 'bg-gradient-to-br from-blue-50 to-sky-100 border-2 border-blue-400 hover:border-blue-500' : '' }}
                        {{ $item['status'] === 'pending' ? 'bg-white border-2 border-gray-200 hover:border-gray-300' : '' }}
                    ">
                        {{-- Status Indicator Dot --}}
                        <div class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full {{ $item['status'] === 'completed' ? 'bg-green-500' : ($item['status'] === 'in_progress' ? 'bg-blue-500' : 'bg-gray-300') }}"></div>

                        {{-- Completion Checkmark --}}
                        @if($item['status'] === 'completed')
                        <div class="absolute top-1 left-1">
                            <div class="flex items-center justify-center w-4 h-4 bg-green-600 rounded-full shadow-sm">
                                <svg class="w-2.5 h-2.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        @endif

                        {{-- Number Badge --}}
                        <div class="flex justify-center mb-1.5">
                            <div class="
                                flex items-center justify-center w-7 h-7 rounded-md text-xs font-bold shadow-sm
                                {{ $item['status'] === 'completed' ? 'bg-green-600 text-white' : '' }}
                                {{ $item['status'] === 'in_progress' ? 'bg-blue-600 text-white' : '' }}
                                {{ $item['status'] === 'pending' ? 'bg-gray-200 text-gray-700' : '' }}
                            ">
                                {{ $item['surah_id'] }}
                            </div>
                        </div>

                        {{-- Surah Name --}}
                        <div class="text-center mb-1.5 px-0.5">
                            <div class="
                                text-xs font-bold leading-tight line-clamp-2 min-h-[2rem] flex items-center justify-center
                                {{ $item['status'] === 'completed' ? 'text-green-800' : '' }}
                                {{ $item['status'] === 'in_progress' ? 'text-blue-800' : '' }}
                                {{ $item['status'] === 'pending' ? 'text-gray-600' : '' }}
                            ">
                                {{ $item['surah_name'] }}
                            </div>
                        </div>

                        {{-- Ayah Count Compact --}}
                        <div class="flex items-center justify-center gap-1 mb-1.5">
                            <span class="text-[10px] font-bold {{ $item['status'] === 'completed' ? 'text-green-700' : ($item['status'] === 'in_progress' ? 'text-blue-700' : 'text-gray-500') }}">
                                {{ $item['memorized_ayahs'] }}/{{ $item['total_ayahs'] }}
                            </span>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="w-full bg-white/70 rounded-full h-1.5 overflow-hidden shadow-inner mb-1">
                            <div
                                class="h-1.5 rounded-full transition-all duration-700 ease-out
                                {{ $item['status'] === 'completed' ? 'bg-gradient-to-r from-green-500 to-green-600' : '' }}
                                {{ $item['status'] === 'in_progress' ? 'bg-gradient-to-r from-blue-500 to-blue-600' : '' }}
                                {{ $item['status'] === 'pending' ? 'bg-gray-300' : '' }}
                                "
                                style="width: {{ $item['progress_percent'] }}%"
                            ></div>
                        </div>

                        {{-- Percentage --}}
                        <div class="text-center">
                            <span class="text-[10px] font-bold {{ $item['status'] === 'completed' ? 'text-green-700' : ($item['status'] === 'in_progress' ? 'text-blue-700' : 'text-gray-500') }}">
                                {{ number_format($item['progress_percent'], 0) }}%
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Compact Info Footer --}}
        <div class="px-5 py-2.5 bg-gradient-to-r from-blue-50 to-sky-50 border-t border-blue-100 rounded-b-xl">
            <div class="flex items-center gap-2">
                <div class="flex items-center justify-center w-5 h-5 bg-blue-500 rounded flex-shrink-0">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-xs text-blue-900">
                    <span class="font-bold">ملاحظة:</span> التقدم من جلسات الحفظ الجديد فقط
                </p>
            </div>
        </div>
    </div>

    {{-- Foundation Skills (Only for foundation track students) --}}
    @if($student->track === 'foundation' && count($foundationSkills) > 0)
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-8">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="p-3 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 text-white shadow-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">مهارات التأسيس</h3>
                    <p class="text-sm text-gray-500 mt-1">نسبة الإتقان لكل مهارة</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($foundationSkills as $skill)
                <div class="p-5 rounded-xl border-2 transition-all {{ $skill['mastery'] >= 80 ? 'bg-green-50 border-green-300 hover:shadow-md hover:shadow-green-200' : ($skill['mastery'] >= 50 ? 'bg-amber-50 border-amber-300 hover:shadow-md hover:shadow-amber-200' : 'bg-red-50 border-red-300 hover:shadow-md hover:shadow-red-200') }}">
                    <div class="flex items-center justify-between mb-3">
                        <span class="font-bold text-gray-900">{{ $skill['name'] }}</span>
                        <span class="text-lg font-bold px-3 py-1 rounded-lg {{ $skill['mastery'] >= 80 ? 'bg-green-600 text-white' : ($skill['mastery'] >= 50 ? 'bg-amber-600 text-white' : 'bg-red-600 text-white') }}">
                            {{ $skill['mastery'] }}%
                        </span>
                    </div>
                    <div class="w-full bg-white/70 rounded-full h-3 overflow-hidden shadow-inner">
                        <div
                            class="h-3 rounded-full transition-all duration-700 ease-out {{ $skill['mastery'] >= 80 ? 'bg-gradient-to-r from-green-500 to-green-600' : ($skill['mastery'] >= 50 ? 'bg-gradient-to-r from-amber-500 to-amber-600' : 'bg-gradient-to-r from-red-500 to-red-600') }}"
                            style="width: {{ $skill['mastery'] }}%"
                        ></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Charts Section --}}
    <div class="space-y-6 mb-8">
        {{-- Monthly Activity Chart --}}
        <x-chart-card
            title="النشاط الشهري"
            subtitle="آيات الحفظ والمراجعة - آخر 6 أشهر"
            height="320px"
        >
            <canvas id="activityChart"></canvas>
        </x-chart-card>

        {{-- Score & Attendance Charts - Two Column Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <x-chart-card
                title="متوسط التقييم"
                subtitle="آخر 6 أشهر"
                height="320px"
            >
                <canvas id="scoreChart"></canvas>
            </x-chart-card>

            <x-chart-card
                title="الحضور"
                subtitle="آخر 6 أشهر"
                height="320px"
            >
                <canvas id="attendanceChart"></canvas>
            </x-chart-card>
        </div>
    </div>

    {{-- Sessions Table --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-8">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="p-3 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 text-white shadow-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">سجل الجلسات</h3>
                    <p class="text-sm text-gray-500 mt-1">جميع جلسات الحفظ والمراجعة</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-right">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                    <tr>
                        <th class="px-6 py-4 font-bold text-gray-700">التاريخ</th>
                        <th class="px-6 py-4 font-bold text-gray-700">النوع</th>
                        <th class="px-6 py-4 font-bold text-gray-700">الحضور</th>
                        <th class="px-6 py-4 font-bold text-gray-700">السورة / الآيات</th>
                        <th class="px-6 py-4 font-bold text-gray-700">عدد الآيات</th>
                        <th class="px-6 py-4 font-bold text-gray-700">التقييم</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($sessions as $session)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-900 font-semibold">
                            {{ $session->date->locale('ar')->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($session->session_type === 'new')
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"/>
                                </svg>
                                جديد
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                                </svg>
                                مراجعة
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($session->attendance_status === 'present')
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                حاضر
                            </span>
                            @elseif($session->attendance_status === 'absent')
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                                غائب
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                معتذر
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            @if ($session->surah)
                            <span class="font-medium text-gray-900">{{ $session->surah->name }}</span>
                            <span class="text-gray-400 mx-1">|</span>
                            <span class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $session->ayah_from }}-{{ $session->ayah_to }}</span>
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1 font-bold text-gray-900 bg-gray-100 px-3 py-1 rounded-lg">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                                </svg>
                                {{ $session->ayah_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <span class="text-base font-bold text-gray-900 w-10">{{ $session->score }}/10</span>
                                <div class="flex-1 max-w-[100px]">
                                    <div class="w-full bg-gray-100 rounded-full h-2.5 shadow-inner">
                                        <div
                                            class="h-2.5 rounded-full transition-all {{ $session->score >= 8 ? 'bg-gradient-to-r from-green-500 to-green-600' : ($session->score >= 5 ? 'bg-gradient-to-r from-amber-500 to-amber-600' : 'bg-gradient-to-r from-red-500 to-red-600') }}"
                                            style="width: {{ ($session->score / 10) * 100 }}%"
                                        ></div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <p class="text-lg font-semibold text-gray-900 mb-1">لا توجد جلسات مسجلة</p>
                                <p class="text-sm text-gray-500">سيتم عرض جلسات الحفظ والمراجعة هنا</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    <x-pagination :paginator="$sessions" align="center" />
</div>

{{-- Chart.js Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1"></script>
<script>
// Chart defaults
Chart.defaults.color = '#64748b';
Chart.defaults.font.family = "'Tajawal', sans-serif";

// Activity Chart
new Chart(document.getElementById('activityChart'), {
    type: 'bar',
    data: {
        labels: @json($monthLabels),
        datasets: [
            {
                label: 'آيات حفظ جديد',
                data: @json($ayahsData),
                backgroundColor: '#10b981',
                borderRadius: 6,
            },
            {
                label: 'آيات مراجعة',
                data: @json($reviewData),
                backgroundColor: '#3b82f6',
                borderRadius: 6,
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    usePointStyle: true
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#f1f5f9' }
            },
            x: {
                grid: { display: false }
            }
        }
    }
});

// Score Chart
new Chart(document.getElementById('scoreChart'), {
    type: 'line',
    data: {
        labels: @json($monthLabels),
        datasets: [{
            label: 'متوسط التقييم',
            data: @json($avgScoreData),
            borderColor: '#0ea5e9',
            backgroundColor: 'rgba(14,165,233,0.1)',
            fill: true,
            tension: 0.4,
            borderWidth: 2,
            pointRadius: 3,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 10,
                grid: { color: '#f1f5f9' }
            },
            x: {
                grid: { display: false }
            }
        }
    }
});

// Attendance Chart
new Chart(document.getElementById('attendanceChart'), {
    type: 'bar',
    data: {
        labels: @json($monthLabels),
        datasets: [
            {
                label: 'حاضر',
                data: @json($presentData),
                backgroundColor: '#10b981',
                borderRadius: 4
            },
            {
                label: 'غائب',
                data: @json($absentData),
                backgroundColor: '#ef4444',
                borderRadius: 4
            },
            {
                label: 'معتذر',
                data: @json($excusedData),
                backgroundColor: '#f59e0b',
                borderRadius: 4
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    usePointStyle: true
                }
            }
        },
        scales: {
            x: {
                stacked: true,
                grid: { display: false }
            },
            y: {
                stacked: true,
                beginAtZero: true,
                grid: { color: '#f1f5f9' }
            }
        }
    }
});
</script>
@endsection
