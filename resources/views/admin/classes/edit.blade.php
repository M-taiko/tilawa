@extends('layouts.app')

@section('title', 'تعديل حلقة')

@section('content')
{{-- Page Header --}}
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">تعديل حلقة</h1>
            <p class="text-sm text-gray-500 mt-1">تحديث بيانات الحلقة <span class="font-semibold text-gray-900">{{ $class->name }}</span></p>
        </div>
        <x-button href="{{ route('admin.classes.index') }}" variant="ghost">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            رجوع
        </x-button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Edit Form --}}
    <div class="lg:col-span-2">
        <x-card class="bg-white border border-gray-200 rounded-xl shadow-sm">
            <x-card-body class="p-6">
                <form method="POST" action="{{ route('admin.classes.update', $class) }}" class="space-y-8">
                    @csrf
                    @method('PUT')

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
                                placeholder="أدخل اسم الحلقة"
                                :value="old('name', $class->name)"
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
                                <option value="men" @if(old('group', $class->group) === 'men') selected @endif>رجال</option>
                                <option value="women" @if(old('group', $class->group) === 'women') selected @endif>نساء</option>
                                <option value="kids" @if(old('group', $class->group) === 'kids') selected @endif>أطفال</option>
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
                                <option value="memorization" @if(old('track', $class->track) === 'memorization') selected @endif>حفظ</option>
                                <option value="foundation" @if(old('track', $class->track) === 'foundation') selected @endif>تأسيس</option>
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
                                <p class="text-sm text-gray-500">اختر المعلم المسؤول عن الحلقة</p>
                            </div>
                        </div>

                        {{-- Teacher --}}
                        <div>
                            <x-select
                                name="teacher_id"
                                label="المعلم"
                                :error="$errors->first('teacher_id')">
                                <x-slot:icon>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </x-slot:icon>
                                <option value="">غير معيّن</option>
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" @if($class->teacher_id === $teacher->id) selected @endif>
                                        {{ $teacher->name }}
                                    </option>
                                @endforeach
                            </x-select>
                            @if($class->teacher)
                                <p class="text-xs text-gray-500 mt-2">
                                    <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    المعلم الحالي: <span class="font-semibold">{{ $class->teacher->name }}</span>
                                </p>
                            @endif
                        </div>

                        {{-- Student Count Warning --}}
                        @if($class->students_count > 0)
                            <x-alert variant="warning">
                                <div>
                                    <p class="font-semibold text-sm mb-1">تنبيه</p>
                                    <p class="text-sm">هذه الحلقة تحتوي على <strong>{{ $class->students_count }}</strong> طالب. تأكد من صحة التعديلات قبل الحفظ.</p>
                                </div>
                            </x-alert>
                        @endif
                    </div>

                    {{-- Form Actions --}}
                    <div class="flex flex-col sm:flex-row items-center gap-3 pt-6 border-t border-gray-200">
                        <x-button type="submit" variant="primary" size="lg" class="w-full sm:w-auto">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            تحديث البيانات
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
    </div>

    {{-- Sidebar --}}
    <div class="lg:col-span-1 space-y-6">
        {{-- Class Status Management --}}
        <x-card class="bg-white border border-gray-200 rounded-xl shadow-sm">
            <x-card-header class="bg-gray-50 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-100">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">حالة الحلقة</h3>
                        <p class="text-xs text-gray-600">إدارة حالة الحلقة</p>
                    </div>
                </div>
            </x-card-header>
            <x-card-body class="space-y-4">
                {{-- Current Status Badge --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">الحالة الحالية</label>
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold
                        @if($class->is_active) bg-green-600 text-white
                        @else bg-red-600 text-white @endif">
                        @if($class->is_active)
                            نشط
                        @else
                            غير نشط
                        @endif
                    </div>
                </div>

                {{-- Toggle Status Button --}}
                <div class="border-t border-gray-200 pt-4">
                    <form method="POST" action="{{ route('admin.classes.toggle-status', $class) }}">
                        @csrf
                        <x-button
                            type="submit"
                            variant="{{ $class->is_active ? 'warning' : 'primary' }}"
                            class="w-full justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if($class->is_active)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                @endif
                            </svg>
                            {{ $class->is_active ? 'تعطيل الحلقة' : 'تفعيل الحلقة' }}
                        </x-button>
                    </form>
                </div>
            </x-card-body>
        </x-card>

        {{-- Class Info --}}
        <x-card class="bg-white border border-gray-200 rounded-xl shadow-sm">
            <x-card-header class="bg-gray-50 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-100">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">معلومات الحلقة</h3>
                        <p class="text-xs text-gray-600">إحصائيات ومعلومات</p>
                    </div>
                </div>
            </x-card-header>
            <x-card-body class="space-y-4">
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-xs font-semibold text-gray-600">عدد الطلاب</span>
                    <span class="text-sm font-bold text-gray-900">{{ $class->students_count ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-xs font-semibold text-gray-600">المجموعة</span>
                    <span class="text-sm font-bold text-gray-900">
                        @php
                            $groupLabels = ['men' => 'رجال', 'women' => 'نساء', 'kids' => 'أطفال'];
                        @endphp
                        {{ $groupLabels[$class->group] ?? $class->group }}
                    </span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <span class="text-xs font-semibold text-gray-600">المسار</span>
                    <span class="text-sm font-bold text-gray-900">
                        @php
                            $trackLabels = ['memorization' => 'حفظ', 'foundation' => 'تأسيس'];
                        @endphp
                        {{ $trackLabels[$class->track] ?? $class->track }}
                    </span>
                </div>
                <div class="flex items-center justify-between py-2">
                    <span class="text-xs font-semibold text-gray-600">تاريخ الإنشاء</span>
                    <span class="text-sm font-bold text-gray-900">{{ $class->created_at->diffForHumans() }}</span>
                </div>
            </x-card-body>
        </x-card>

        {{-- Danger Zone --}}
        <x-card variant="red" class="bg-white border border-red-200 rounded-xl shadow-sm">
            <x-card-header class="bg-red-50 border-b border-red-200">
                <div class="flex items-center gap-3">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-red-100">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900">منطقة الخطر</h3>
                        <p class="text-xs text-gray-600">حذف الحلقة نهائياً</p>
                    </div>
                </div>
            </x-card-header>
            <x-card-body class="space-y-4">
                <p class="text-sm text-gray-600">سيتم حذف الحلقة وجميع البيانات المرتبطة بها بشكل دائم. هذا الإجراء لا يمكن التراجع عنه.</p>
                <form method="POST" action="{{ route('admin.classes.destroy', $class) }}">
                    @csrf
                    @method('DELETE')
                    <x-button
                        type="submit"
                        variant="danger"
                        class="w-full justify-center"
                        onclick="return confirm('هل أنت متأكد من حذف هذه الحلقة؟\n\nسيتم حذف:\n- معلومات الحلقة\n- ارتباط {{ $class->students_count ?? 0 }} طالب\n- جميع البيانات المرتبطة\n\nهذا الإجراء لا يمكن التراجع عنه!')">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        حذف الحلقة
                    </x-button>
                </form>
            </x-card-body>
        </x-card>
    </div>
</div>
@endsection
