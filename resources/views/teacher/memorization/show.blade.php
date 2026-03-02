@extends('layouts.app')

@section('title', 'متابعة حفظ ' . $student->name)

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush

@section('content')
@php
    $surahs = \App\Models\Surah::all();
@endphp

<div class="min-h-screen p-4 md:p-6 pattern-subtle">
    {{-- Header --}}
    <div class="mb-6 animate-fadeInUp">
        <div class="max-w-7xl mx-auto">
            <x-button variant="ghost" href="{{ route('teacher.students.index') }}" class="mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                </svg>
                رجوع للطلاب
            </x-button>

            <x-card islamic class="p-6 bg-gradient-to-r from-emerald-50 to-gold-50">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-emerald-800 mb-2">
                            متابعة حفظ {{ $student->name }}
                        </h1>
                        <p class="text-slate-600">
                            @if($student->class)
                                حلقة: <span class="font-semibold text-emerald-700">{{ $student->class->name }}</span>
                            @endif
                        </p>
                    </div>
                    <x-button variant="gold" href="{{ route('teacher.memorization.open-quran', $student) }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        فتح المصحف
                    </x-button>
                </div>
            </x-card>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="max-w-7xl mx-auto mb-6 animate-fadeInUp stagger-1">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <x-card class="p-4 bg-emerald-50 border-2 border-emerald-200">
                <div class="text-center">
                    <div class="text-3xl font-bold text-emerald-700">{{ $stats['completed_assignments'] }}</div>
                    <div class="text-sm text-slate-600 mt-1">مقاطع مكتملة</div>
                </div>
            </x-card>

            <x-card class="p-4 bg-blue-50 border-2 border-blue-200">
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-700">{{ $stats['in_progress_assignments'] }}</div>
                    <div class="text-sm text-slate-600 mt-1">قيد الحفظ</div>
                </div>
            </x-card>

            <x-card class="p-4 bg-red-50 border-2 border-red-200">
                <div class="text-center">
                    <div class="text-3xl font-bold text-red-700">{{ $stats['overdue_assignments'] }}</div>
                    <div class="text-sm text-slate-600 mt-1">متأخرة</div>
                </div>
            </x-card>

            <x-card class="p-4 bg-gold-50 border-2 border-gold-200">
                <div class="text-center">
                    <div class="text-3xl font-bold text-gold-700">
                        {{ $stats['avg_test_score'] ? number_format($stats['avg_test_score'], 1) : '-' }}
                    </div>
                    <div class="text-sm text-slate-600 mt-1">متوسط الاختبارات</div>
                </div>
            </x-card>
        </div>
    </div>

    {{-- Current Page Info --}}
    @if($currentPage)
    <div class="max-w-7xl mx-auto mb-6 animate-fadeInUp stagger-2">
        <x-card islamic class="p-6 bg-gradient-to-br from-amber-50 to-white">
            <h3 class="font-bold text-lg text-amber-800 mb-3">الموضع الحالي</h3>
            <div class="grid md:grid-cols-3 gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm text-slate-600">الصفحة</div>
                        <div class="font-bold text-xl text-amber-800">{{ $currentPage['page_number'] }}</div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm text-slate-600">السورة</div>
                        <div class="font-bold text-emerald-800">{{ $currentPage['surah_name'] }}</div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-gold-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm text-slate-600">النطاق</div>
                        <div class="font-bold text-gold-800">
                            من {{ $currentPage['highlight_start'] }} إلى {{ $currentPage['highlight_end'] }}
                        </div>
                    </div>
                </div>
            </div>
        </x-card>
    </div>
    @endif

    {{-- Progress Map --}}
    <div class="max-w-7xl mx-auto mb-6 animate-fadeInUp stagger-3">
        <x-card islamic class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-lg text-emerald-800">خريطة التقدم - السور</h3>
                <span class="text-sm text-slate-600">114 سورة</span>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-96 overflow-y-auto p-2">
                @foreach($progressMap as $surah)
                <div class="surah-card p-4 rounded-xl border-2 transition-all hover:shadow-md @if($surah['status'] === 'completed') bg-emerald-50 border-emerald-200 @elseif($surah['status'] === 'in_progress') bg-blue-50 border-blue-200 @else bg-slate-50 border-slate-200 @endif">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-bold text-sm @if($surah['status'] === 'completed') text-emerald-800 @elseif($surah['status'] === 'in_progress') text-blue-800 @else text-slate-700 @endif">
                            {{ $surah['surah_name'] }}
                        </h4>
                        <span class="text-xs px-2 py-1 rounded-full @if($surah['status'] === 'completed') bg-emerald-200 text-emerald-800 @elseif($surah['status'] === 'in_progress') bg-blue-200 text-blue-800 @else bg-slate-200 text-slate-700 @endif">
                            {{ number_format($surah['progress_percent'], 1) }}%
                        </span>
                    </div>

                    <x-progress
                        :value="$surah['progress_percent']"
                        :max="100"
                        class="mb-2"
                        :color="$surah['status'] === 'completed' ? 'emerald' : ($surah['status'] === 'in_progress' ? 'blue' : 'slate')"
                    />

                    <div class="flex items-center justify-between text-xs text-slate-600">
                        <span>{{ $surah['memorized_ayahs'] }} / {{ $surah['total_ayahs'] }} آية</span>
                        @if($surah['avg_score'])
                            <span class="font-semibold text-gold-700">{{ number_format($surah['avg_score'], 1) }}/10</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </x-card>
    </div>

    {{-- Assignments & Tests Tabs --}}
    <div class="max-w-7xl mx-auto mb-6 animate-fadeInUp stagger-4">
        <x-card islamic class="p-6">
            <div class="border-b border-slate-200 mb-4">
                <nav class="-mb-px flex gap-4" x-data="{ tab: 'assignments' }">
                    <button @click="tab = 'assignments'"
                            :class="tab === 'assignments' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                            class="py-2 px-4 border-b-2 font-medium text-sm transition-colors">
                        المقاطع المعينة
                    </button>
                    <button @click="tab = 'tests'"
                            :class="tab === 'tests' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'"
                            class="py-2 px-4 border-b-2 font-medium text-sm transition-colors">
                        الاختبارات
                    </button>
                </nav>
            </div>

            {{-- Assignments Tab --}}
            <div x-show="tab === 'assignments'">
                <div class="mb-4">
                    <x-button variant="gold" size="sm" @click="$dispatch('open-modal', 'create-assignment')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        مقطع جديد
                    </x-button>
                </div>

                @if($assignments->isEmpty())
                    <p class="text-center text-slate-500 py-8">لا توجد مقاطع معينة بعد</p>
                @else
                    <div class="space-y-3">
                        @foreach($assignments as $assignment)
                        <div class="p-4 rounded-lg border-2 @if($assignment->status === 'completed') bg-emerald-50 border-emerald-200 @elseif($assignment->isOverdue()) bg-red-50 border-red-200 @else bg-blue-50 border-blue-200 @endif">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4 class="font-bold text-emerald-800">{{ $assignment->surah->name_arabic }}</h4>
                                    <p class="text-sm text-slate-600 mt-1">
                                        من آية {{ $assignment->start_ayah }} إلى {{ $assignment->end_ayah }}
                                        <span class="mx-1">•</span>
                                        {{ $assignment->ayah_count }} آية
                                    </p>
                                    @if($assignment->notes)
                                        <p class="text-xs text-slate-500 mt-2">{{ $assignment->notes }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold @if($assignment->status === 'completed') bg-emerald-200 text-emerald-800 @elseif($assignment->isOverdue()) bg-red-200 text-red-800 @else bg-blue-200 text-blue-800 @endif">
                                        @if($assignment->status === 'assigned') معين @elseif($assignment->status === 'in_progress') قيد الحفظ @elseif($assignment->status === 'completed') مكتمل @else مؤجل @endif
                                    </span>
                                    @if($assignment->due_date)
                                        <p class="text-xs text-slate-500 mt-2">موعد: {{ $assignment->due_date->format('Y-m-d') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        {{ $assignments->links() }}
                    </div>
                @endif
            </div>

            {{-- Tests Tab --}}
            <div x-show="tab === 'tests'" style="display: none;">
                <div class="mb-4">
                    <x-button variant="gold" size="sm" @click="$dispatch('open-modal', 'create-test')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        اختبار جديد
                    </x-button>
                </div>

                @if($tests->isEmpty())
                    <p class="text-center text-slate-500 py-8">لا توجد اختبارات بعد</p>
                @else
                    <div class="space-y-3">
                        @foreach($tests as $test)
                        <div class="p-4 rounded-lg border-2 bg-gold-50 border-gold-200">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4 class="font-bold text-gold-800">{{ $test->surah->name_arabic }}</h4>
                                    <p class="text-sm text-slate-600 mt-1">
                                        من آية {{ $test->start_ayah }} إلى {{ $test->end_ayah }}
                                        <span class="mx-1">•</span>
                                        تاريخ: {{ $test->test_date->format('Y-m-d') }}
                                    </p>
                                    @if($test->notes)
                                        <p class="text-xs text-slate-500 mt-2">{{ $test->notes }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-gold-700">{{ $test->total_score }}/100</div>
                                    @if($test->mistakes_count > 0)
                                        <p class="text-xs text-red-600 mt-1">{{ $test->mistakes_count }} خطأ</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </x-card>
    </div>
</div>

{{-- Create Assignment Modal --}}
<div x-data="{ open: false }" @open-modal.window="if ($event.detail === 'create-assignment') open = true">
    <div x-show="open" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div x-show="open"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-black bg-opacity-50"
                 @click="open = false"></div>

            <div x-show="open"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-4"
                 class="relative bg-white rounded-2xl shadow-xl max-w-2xl w-full p-6 z-50">

                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-emerald-800">مقطع حفظ جديد</h3>
                    <button @click="open = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('teacher.memorization.assignments.store', $student) }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">السورة *</label>
                            <select name="surah_id" required class="w-full px-4 py-2 border-2 border-slate-200 rounded-lg focus:ring-4 focus:ring-emerald-500/30 focus:border-emerald-500">
                                <option value="">اختر السورة</option>
                                @php
                                    $surahs = \App\Models\Surah::all();
                                @endphp
                                @foreach($surahs as $surah)
                                    <option value="{{ $surah->id }}">{{ $surah->name_arabic }} ({{ $surah->ayah_count }} آية)</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">من آية *</label>
                                <input type="number" name="start_ayah" min="1" required
                                       class="w-full px-4 py-2 border-2 border-slate-200 rounded-lg focus:ring-4 focus:ring-emerald-500/30 focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">إلى آية *</label>
                                <input type="number" name="end_ayah" min="1" required
                                       class="w-full px-4 py-2 border-2 border-slate-200 rounded-lg focus:ring-4 focus:ring-emerald-500/30 focus:border-emerald-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">الموعد النهائي</label>
                            <input type="date" name="due_date"
                                   class="w-full px-4 py-2 border-2 border-slate-200 rounded-lg focus:ring-4 focus:ring-emerald-500/30 focus:border-emerald-500">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">ملاحظات</label>
                            <textarea name="notes" rows="3"
                                      class="w-full px-4 py-2 border-2 border-slate-200 rounded-lg focus:ring-4 focus:ring-emerald-500/30 focus:border-emerald-500"></textarea>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <x-button type="submit" variant="gold" class="flex-1">
                                إنشاء المقطع
                            </x-button>
                            <x-button type="button" variant="ghost" @click="open = false">
                                إلغاء
                            </x-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Create Test Modal --}}
<div x-data="{ open: false }" @open-modal.window="if ($event.detail === 'create-test') open = true">
    <div x-show="open" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div x-show="open"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-black bg-opacity-50"
                 @click="open = false"></div>

            <div x-show="open"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 translate-y-4"
                 class="relative bg-white rounded-2xl shadow-xl max-w-2xl w-full p-6 z-50">

                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gold-800">اختبار جديد</h3>
                    <button @click="open = false" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('teacher.memorization.tests.store', $student) }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">السورة *</label>
                            <select name="surah_id" required class="w-full px-4 py-2 border-2 border-slate-200 rounded-lg focus:ring-4 focus:ring-gold-500/30 focus:border-gold-500">
                                <option value="">اختر السورة</option>
                                @foreach($surahs as $surah)
                                    <option value="{{ $surah->id }}">{{ $surah->name_arabic }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">من آية *</label>
                                <input type="number" name="start_ayah" min="1" required
                                       class="w-full px-4 py-2 border-2 border-slate-200 rounded-lg focus:ring-4 focus:ring-gold-500/30 focus:border-gold-500">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">إلى آية *</label>
                                <input type="number" name="end_ayah" min="1" required
                                       class="w-full px-4 py-2 border-2 border-slate-200 rounded-lg focus:ring-4 focus:ring-gold-500/30 focus:border-gold-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">الدرجة الكلية (من 100) *</label>
                            <input type="number" name="total_score" min="0" max="100" required
                                   class="w-full px-4 py-2 border-2 border-slate-200 rounded-lg focus:ring-4 focus:ring-gold-500/30 focus:border-gold-500">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">دقة الحفظ (من 100)</label>
                                <input type="number" name="memorization_accuracy" min="0" max="100"
                                       class="w-full px-4 py-2 border-2 border-slate-200 rounded-lg focus:ring-4 focus:ring-gold-500/30 focus:border-gold-500">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">جودة التجويد (من 100)</label>
                                <input type="number" name="tajweed_quality" min="0" max="100"
                                       class="w-full px-4 py-2 border-2 border-slate-200 rounded-lg focus:ring-4 focus:ring-gold-500/30 focus:border-gold-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">عدد الأخطاء</label>
                                <input type="number" name="mistakes_count" min="0" value="0"
                                       class="w-full px-4 py-2 border-2 border-slate-200 rounded-lg focus:ring-4 focus:ring-gold-500/30 focus:border-gold-500">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">نوع الاختبار *</label>
                                <select name="test_type" required class="w-full px-4 py-2 border-2 border-slate-200 rounded-lg focus:ring-4 focus:ring-gold-500/30 focus:border-gold-500">
                                    <option value="sequential">تسلسلي</option>
                                    <option value="random">عشوائي</option>
                                    <option value="full_surah">سورة كاملة</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">تاريخ الاختبار *</label>
                            <input type="date" name="test_date" value="{{ date('Y-m-d') }}" required
                                   class="w-full px-4 py-2 border-2 border-slate-200 rounded-lg focus:ring-4 focus:ring-gold-500/30 focus:border-gold-500">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">ملاحظات</label>
                            <textarea name="notes" rows="3"
                                      class="w-full px-4 py-2 border-2 border-slate-200 rounded-lg focus:ring-4 focus:ring-gold-500/30 focus:border-gold-500"></textarea>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <x-button type="submit" variant="gold" class="flex-1">
                                حفظ الاختبار
                            </x-button>
                            <x-button type="button" variant="ghost" @click="open = false">
                                إلغاء
                            </x-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
