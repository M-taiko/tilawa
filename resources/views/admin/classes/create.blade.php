@extends('layouts.app')

@section('title', 'إضافة حلقة')

@section('content')
{{-- Page Header --}}
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">إضافة حلقة</h1>
            <p class="text-sm text-gray-500 mt-1">إضافة حلقة جديدة للمركز</p>
        </div>
        <x-button href="{{ route('admin.classes.index') }}" variant="ghost">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            رجوع
        </x-button>
    </div>
</div>

{{-- Class Form --}}
<x-card class="bg-white border border-gray-200 rounded-xl shadow-sm">
    <x-card-body class="p-6">
        <form method="POST" action="{{ route('admin.classes.store') }}" class="space-y-8">
            @csrf

            {{-- Section 1: Class Information --}}
            <div class="space-y-6">
                <div class="flex items-center gap-3 pb-3 border-b border-gray-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">معلومات الحلقة</h2>
                        <p class="text-sm text-gray-500">البيانات الأساسية للحلقة</p>
                    </div>
                </div>

                {{-- Class Name --}}
                <div>
                    <x-input
                        name="name"
                        label="اسم الحلقة"
                        type="text"
                        placeholder="مثال: حلقة التحفيظ المسائية"
                        :value="old('name')"
                        required
                        :error="$errors->first('name')">
                        <x-slot name="icon">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </x-slot>
                    </x-input>
                </div>

                {{-- Group & Track --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-select
                        name="group"
                        label="المجموعة"
                        required
                        :error="$errors->first('group')">
                        <x-slot:icon>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </x-slot:icon>
                        <option value="">اختر المجموعة</option>
                        <option value="men" {{ old('group') == 'men' ? 'selected' : '' }}>رجال</option>
                        <option value="women" {{ old('group') == 'women' ? 'selected' : '' }}>نساء</option>
                        <option value="kids" {{ old('group') == 'kids' ? 'selected' : '' }}>أطفال</option>
                    </x-select>

                    <x-select
                        name="track"
                        label="المسار"
                        required
                        :error="$errors->first('track')">
                        <x-slot:icon>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </x-slot:icon>
                        <option value="">اختر المسار</option>
                        <option value="memorization" {{ old('track') == 'memorization' ? 'selected' : '' }}>حفظ</option>
                        <option value="foundation" {{ old('track') == 'foundation' ? 'selected' : '' }}>تأسيس</option>
                    </x-select>
                </div>
            </div>

            {{-- Section 2: Teacher Assignment --}}
            <div class="space-y-6">
                <div class="flex items-center gap-3 pb-3 border-b border-gray-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">تعيين المعلم</h2>
                        <p class="text-sm text-gray-500">اختر المعلم المسؤول عن الحلقة (اختياري)</p>
                    </div>
                </div>

                {{-- Teacher --}}
                <div>
                    <x-select
                        name="teacher_id"
                        label="المعلم (اختياري)"
                        :error="$errors->first('teacher_id')">
                        <x-slot:icon>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </x-slot:icon>
                        <option value="">غير معيّن (سيتم التعيين لاحقاً)</option>
                        @forelse ($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @empty
                            <option value="" disabled>لا يوجد معلمون نشطون</option>
                        @endforelse
                    </x-select>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex flex-col sm:flex-row items-center gap-3 pt-6 border-t border-gray-200">
                <x-button type="submit" variant="primary" size="lg" class="w-full sm:w-auto">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    حفظ الحلقة
                </x-button>
                <x-button href="{{ route('admin.classes.index') }}" variant="outline" size="lg" class="w-full sm:w-auto">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    إلغاء
                </x-button>
            </div>
        </form>
    </x-card-body>
</x-card>
@endsection
