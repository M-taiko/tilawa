@extends('layouts.app')

@section('title', 'البحث في القرآن الكريم - Tilawa')

@section('content')
<div class="min-h-screen p-4 md:p-6 pattern-subtle">
    {{-- Header --}}
    <div class="mb-6 md:mb-8 text-center animate-fadeInUp">
        <div class="flex items-center justify-center gap-4 mb-4">
            <x-button variant="ghost" href="{{ route('quran.index') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                </svg>
                {{ app()->getLocale() === 'ar' ? 'رجوع للمصحف' : 'Back to Quran' }}
            </x-button>

            {{-- Language Toggle --}}
            <div class="flex items-center gap-1 bg-slate-100 rounded-lg p-1">
                <form method="POST" action="{{ route('locale.switch') }}" class="inline">
                    @csrf
                    <input type="hidden" name="locale" value="ar">
                    <button type="submit" class="px-2.5 py-1.5 rounded-md text-xs font-semibold transition-colors {{ app()->getLocale() === 'ar' ? 'bg-white text-primary-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}" style="font-family:'Tajawal',sans-serif;">
                        العربية
                    </button>
                </form>
                <form method="POST" action="{{ route('locale.switch') }}" class="inline">
                    @csrf
                    <input type="hidden" name="locale" value="en">
                    <button type="submit" class="px-2.5 py-1.5 rounded-md text-xs font-semibold transition-colors {{ app()->getLocale() === 'en' ? 'bg-white text-primary-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}" style="font-family:'Tajawal',sans-serif;">
                        EN
                    </button>
                </form>
            </div>
        </div>

        <div class="inline-block p-4 md:p-6 rounded-2xl bg-gradient-to-br from-accent-50 to-emerald-50 border-2 border-accent-300 shadow-lg">
            <svg class="w-14 h-14 md:w-16 md:h-16 mx-auto text-accent-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <h1 class="text-2xl md:text-3xl font-bold heading-islamic text-accent-800 mb-2">{{ app()->getLocale() === 'ar' ? 'البحث في القرآن الكريم' : 'Search the Quran' }}</h1>
            <p class="text-accent-700">{{ app()->getLocale() === 'ar' ? 'ابحث عن أي كلمة أو عبارة في القرآن' : 'Search for any word or phrase in the Quran' }}</p>
        </div>
    </div>

    {{-- Search Form --}}
    <div class="max-w-4xl mx-auto mb-8 animate-fadeInUp stagger-1">
        <x-card islamic class="p-6 md:p-8">
            <form method="POST" action="{{ route('quran.search') }}" class="space-y-6">
                @csrf

                {{-- Search Input --}}
                <div>
                    <label class="block text-sm font-bold text-accent-800 mb-3">
                        <span class="text-gold-600">*</span> {{ app()->getLocale() === 'ar' ? 'ابحث عن كلمة أو عبارة' : 'Search for a word or phrase' }}
                    </label>
                    <div class="relative">
                        <input
                            type="text"
                            name="q"
                            required
                            minlength="3"
                            placeholder="{{ app()->getLocale() === 'ar' ? 'مثال: الحمد لله' : 'Example: Allah' }}"
                            class="w-full px-4 py-4 pr-12 border-2 border-accent-200 rounded-xl focus:ring-4 focus:ring-accent-500/30 focus:border-accent-500 transition-all text-lg"
                            value="{{ old('q') }}"
                        >
                        <div class="absolute left-4 top-1/2 -translate-y-1/2">
                            <svg class="w-6 h-6 text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                    @error('q')
                        <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Filters --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Filter by Surah --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">{{ app()->getLocale() === 'ar' ? 'تحديد السورة (اختياري)' : 'Select Surah (Optional)' }}</label>
                        <select
                            name="surah_id"
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-4 focus:ring-accent-500/30 focus:border-accent-500 transition-all"
                        >
                            <option value="">{{ app()->getLocale() === 'ar' ? 'جميع السور' : 'All Surahs' }}</option>
                            @foreach($surahs as $surah)
                                <option value="{{ $surah->id }}" {{ old('surah_id') == $surah->id ? 'selected' : '' }}>
                                    {{ app()->getLocale() === 'ar' ? $surah->name_arabic : $surah->name_english }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter by Juz --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">{{ app()->getLocale() === 'ar' ? 'تحديد الجزء (اختياري)' : 'Select Juz (Optional)' }}</label>
                        <select
                            name="juz_number"
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-4 focus:ring-accent-500/30 focus:border-accent-500 transition-all"
                        >
                            <option value="">{{ app()->getLocale() === 'ar' ? 'جميع الأجزاء' : 'All Juzs' }}</option>
                            @for($i = 1; $i <= 30; $i++)
                                <option value="{{ $i }}" {{ old('juz_number') == $i ? 'selected' : '' }}>
                                    {{ app()->getLocale() === 'ar' ? 'الجزء ' . $i : 'Juz ' . $i }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    {{-- Filter by Page --}}
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">{{ app()->getLocale() === 'ar' ? 'تحديد الصفحة (اختياري)' : 'Select Page (Optional)' }}</label>
                        <input
                            type="number"
                            name="page_number"
                            min="1"
                            max="604"
                            placeholder="1-604"
                            class="w-full px-4 py-3 border-2 border-slate-200 rounded-xl focus:ring-4 focus:ring-accent-500/30 focus:border-accent-500 transition-all"
                            value="{{ old('page_number') }}"
                        >
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="flex justify-center pt-4">
                    <x-button type="submit" variant="gold" class="px-8 py-4 text-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        {{ app()->getLocale() === 'ar' ? 'بحث' : 'Search' }}
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>

    {{-- Search Tips --}}
    <div class="max-w-4xl mx-auto animate-fadeInUp stagger-2">
        <x-card variant="info" class="p-6">
            <div class="flex gap-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 rounded-xl bg-accent-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="font-bold text-accent-800 mb-3">نصائح البحث</h3>
                    <ul class="text-sm text-slate-600 space-y-2">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            يمكنك البحث بكلمة واحدة أو عدة كلمات
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            البحث يعمل على النص الكامل بالتشكيل
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            استخدم الفلاتر لتضييق نطاق البحث
                        </li>
                    </ul>
                </div>
            </div>
        </x-card>
    </div>
</div>
@endsection
