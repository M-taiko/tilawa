@extends('layouts.app')

@section('title', 'تعديل الرسوم - Tilawa')

@section('content')
<div class="min-h-screen p-4 md:p-6 pattern-subtle">
    {{-- Header --}}
    <div class="mb-6 md:mb-8 animate-fadeInUp">
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 mb-4">
            <x-button variant="ghost" href="{{ route('admin.student-fees.index') }}" size="sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                </svg>
                <span class="hidden sm:inline">رجوع</span>
            </x-button>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold heading-islamic text-shadow-emerald">تعديل الرسوم</h1>
                <p class="text-sm text-primary-600 mt-1">{{ $studentFee->student->name }}</p>
            </div>
        </div>
    </div>

    <div class="max-w-2xl mx-auto">
        <x-card islamic class="p-6 md:p-8">
            <form method="POST" action="{{ route('admin.student-fees.update', $studentFee) }}" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Current Info Card --}}
                <div class="bg-primary-50 border-2 border-primary-200 rounded-xl p-4 mb-6">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-primary-800">معلومات الطالب</h3>
                            <p class="text-sm text-primary-600">{{ $studentFee->student->name }}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-slate-600">الرسوم الحالية:</span>
                            <span class="font-bold text-gold-600">{{ number_format($studentFee->monthly_fee, 0) }} ريال</span>
                        </div>
                        <div>
                            <span class="text-slate-600">تاريخ البدء:</span>
                            <span class="font-semibold text-slate-800">{{ $studentFee->effective_from->format('Y/m/d') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Monthly Fee --}}
                <div>
                    <label class="block text-sm font-bold text-primary-800 mb-2">
                        <span class="text-gold-600">*</span> الرسوم الشهرية الجديدة
                    </label>
                    <div class="relative">
                        <input
                            type="number"
                            name="monthly_fee"
                            min="0"
                            step="0.01"
                            value="{{ old('monthly_fee', $studentFee->monthly_fee) }}"
                            required
                            class="w-full px-4 py-3 pr-20 border-2 border-primary-200 rounded-xl focus:ring-4 focus:ring-gold-500/30 focus:border-gold-500 transition-all"
                        >
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-semibold">ريال</span>
                    </div>
                    @error('monthly_fee')
                        <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Effective From --}}
                <div>
                    <label class="block text-sm font-bold text-primary-800 mb-2">
                        تاريخ بدء التطبيق
                    </label>
                    <input
                        type="date"
                        name="effective_from"
                        value="{{ old('effective_from', $studentFee->effective_from->format('Y-m-d')) }}"
                        class="w-full px-4 py-3 border-2 border-primary-200 rounded-xl focus:ring-4 focus:ring-gold-500/30 focus:border-gold-500 transition-all"
                    >
                    @error('effective_from')
                        <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Notes --}}
                <div>
                    <label class="block text-sm font-bold text-primary-800 mb-2">ملاحظات</label>
                    <textarea
                        name="notes"
                        rows="3"
                        class="w-full px-4 py-3 border-2 border-primary-200 rounded-xl focus:ring-4 focus:ring-gold-500/30 focus:border-gold-500 transition-all"
                        placeholder="سبب التعديل أو أي ملاحظات..."
                    >{{ old('notes', $studentFee->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit Buttons --}}
                <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t-2 border-gold-200">
                    <x-button type="submit" variant="gold" class="flex-1 justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        حفظ التعديلات
                    </x-button>
                    <x-button type="button" variant="secondary" href="{{ route('admin.student-fees.index') }}" class="justify-center">
                        إلغاء
                    </x-button>
                </div>
            </form>
        </x-card>

        {{-- Warning Card --}}
        <x-card variant="warning" class="p-4 md:p-6 mt-6">
            <div class="flex gap-3 md:gap-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl bg-warning-100 flex items-center justify-center">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-warning-800 mb-2">تنبيه هام</h3>
                    <ul class="text-sm text-slate-600 space-y-1">
                        <li>• سيتم إلغاء الرسوم الحالية وإنشاء رسوم جديدة</li>
                        <li>• المدفوعات السابقة لن تتأثر</li>
                        <li>• التعديل سيطبق من التاريخ المحدد</li>
                    </ul>
                </div>
            </div>
        </x-card>
    </div>
</div>
@endsection
