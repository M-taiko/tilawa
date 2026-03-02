@extends('layouts.app')

@section('title', 'تحديد رسوم - Tilawa')

@section('content')
<div class="min-h-screen p-6 pattern-subtle">
    {{-- Header --}}
    <div class="mb-8 animate-fadeInUp">
        <div class="flex items-center gap-4 mb-4">
            <x-button variant="ghost" href="{{ route('admin.student-fees.index') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                </svg>
                رجوع
            </x-button>
            <h1 class="text-3xl font-bold heading-islamic text-shadow-emerald">تحديد رسوم جديدة</h1>
        </div>
        <p class="text-primary-600">تحديد الرسوم الشهرية لطالب أو مجموعة من الطلاب</p>
    </div>

    <div class="max-w-2xl">
        <x-card islamic class="p-8">
            <form method="POST" action="{{ route('admin.student-fees.store') }}" class="space-y-6">
                @csrf

                {{-- Student Selection --}}
                <div>
                    <label class="block text-sm font-bold text-primary-800 mb-2">
                        <span class="text-gold-600">*</span> الطالب
                    </label>
                    <select
                        name="student_id"
                        required
                        class="w-full px-4 py-3 border-2 border-primary-200 rounded-xl focus:ring-4 focus:ring-gold-500/30 focus:border-gold-500 transition-all"
                    >
                        <option value="">-- اختر الطالب --</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id', request('student_id')) == $student->id ? 'selected' : '' }}>
                                {{ $student->name }} -
                                @if($student->group === 'men') رجال
                                @elseif($student->group === 'women') نساء
                                @else أطفال
                                @endif
                                @if($student->class) - {{ $student->class->name }} @endif
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Monthly Fee --}}
                <div>
                    <label class="block text-sm font-bold text-primary-800 mb-2">
                        <span class="text-gold-600">*</span> الرسوم الشهرية
                    </label>
                    <div class="relative">
                        <input
                            type="number"
                            name="monthly_fee"
                            min="0"
                            step="0.01"
                            required
                            placeholder="500"
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
                        تاريخ البدء
                    </label>
                    <input
                        type="date"
                        name="effective_from"
                        value="{{ date('Y-m-01') }}"
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
                        placeholder="أي ملاحظات إضافية..."
                    ></textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit Buttons --}}
                <div class="flex gap-4 pt-4 border-t-2 border-gold-200">
                    <x-button type="submit" variant="gold" class="flex-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        حفظ الرسوم
                    </x-button>
                    <x-button type="button" variant="secondary" href="{{ route('admin.student-fees.index') }}">
                        إلغاء
                    </x-button>
                </div>
            </form>
        </x-card>

        {{-- Help Card --}}
        <x-card variant="info" class="p-6 mt-6">
            <div class="flex gap-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 rounded-xl bg-accent-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="font-bold text-accent-800 mb-2">نصائح هامة</h3>
                    <ul class="text-sm text-slate-600 space-y-1">
                        <li>• سيتم تطبيق الرسوم ابتداءً من تاريخ البدء المحدد</li>
                        <li>• يمكن تحديث الرسوم لاحقاً من قائمة الرسوم</li>
                        <li>• الرسوم الجديدة ستلغي الرسوم القديمة تلقائياً</li>
                    </ul>
                </div>
            </div>
        </x-card>
    </div>
</div>
@endsection
