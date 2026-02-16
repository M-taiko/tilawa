@extends('layouts.app')

@section('title', 'تعديل مهارة')

@section('content')
{{-- Page Header --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
 <div>
 <h1 class="text-3xl font-bold text-gray-900">تعديل مهارة تأسيسية</h1>
 <p class="text-sm text-gray-600 mt-1">تحديث بيانات المهارة {{ $foundationSkill->name }}</p>
 </div>
 <x-button href="{{ route('admin.foundation-skills.index') }}" variant="ghost">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
 </svg>
 رجوع
 </x-button>
</div>

{{-- Edit Form --}}
<x-card>
 <x-card-body>
 <form method="POST" action="{{ route('admin.foundation-skills.update', $foundationSkill) }}" class="space-y-6">
 @csrf
 @method('PUT')

 {{-- Skill Name --}}
 <x-input
 name="name_ar"
 label="اسم المهارة"
 type="text"
 placeholder="مثال: الحروف، الفتحة، المد"
 :value="old('name_ar', $foundationSkill->name)"
 required
 :error="$errors->first('name_ar')"
 >
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
 </svg>
 </x-slot>
 </x-input>

 {{-- Sort Order --}}
 <x-input
 name="sort_order"
 label="ترتيب العرض"
 type="number"
 placeholder="0"
 :value="old('sort_order', $foundationSkill->sort_order)"
 :error="$errors->first('sort_order')"
 helper="يحدد ترتيب ظهور المهارة في القوائم"
 >
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
 </svg>
 </x-slot>
 </x-input>

 {{-- Active Status --}}
 <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-lg border border-gray-200">
 <input
 type="checkbox"
 name="is_active"
 value="1"
 id="is_active"
 @if(old('is_active', $foundationSkill->is_active)) checked @endif
 class="w-4 h-4 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
 >
 <label for="is_active" class="text-sm font-medium text-gray-700 cursor-pointer">
 تفعيل المهارة (تظهر للمعلمين والطلاب)
 </label>
 </div>

 {{-- Form Actions --}}
 <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
 <x-button type="submit" variant="primary" size="lg">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
 </svg>
 تحديث المهارة
 </x-button>
 <x-button href="{{ route('admin.foundation-skills.index') }}" variant="outline" size="lg">
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
