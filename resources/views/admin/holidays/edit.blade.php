@extends('layouts.app')

@section('title', 'تعديل إجازة')

@section('content')
<div class="max-w-3xl mx-auto">
 <div class="mb-8">
 <h1 class="text-3xl font-bold text-gray-900 ">تعديل إجازة</h1>
 <p class="text-sm text-gray-500 mt-1">تحديث معلومات الإجازة</p>
 </div>

 <div class="bg-white border border-gray-200 rounded-lg p-6">
 <form action="{{ route('admin.holidays.update', $holiday) }}" method="POST" class="space-y-6">
 @csrf
 @method('PUT')

 {{-- Name --}}
 <div>
 <label class="block text-sm font-medium text-gray-700 mb-2">
 اسم الإجازة <span class="text-red-600">*</span>
 </label>
 <input type="text" name="name" value="{{ old('name', $holiday->name) }}" required
 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 ">
 @error('name')
 <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
 @enderror
 </div>

 {{-- Description --}}
 <div>
 <label class="block text-sm font-medium text-gray-700 mb-2">
 الوصف (اختياري)
 </label>
 <textarea name="description" rows="3"
 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 ">{{ old('description', $holiday->description) }}</textarea>
 @error('description')
 <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
 @enderror
 </div>

 {{-- Type --}}
 <div>
 <label class="block text-sm font-medium text-gray-700 mb-2">
 النوع <span class="text-red-600">*</span>
 </label>
 <select name="type" required
 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 ">
 <option value="">اختر النوع</option>
 <option value="holiday" {{ old('type', $holiday->type) === 'holiday' ? 'selected' : '' }}>عطلة رسمية</option>
 <option value="vacation" {{ old('type', $holiday->type) === 'vacation' ? 'selected' : '' }}>إجازة</option>
 <option value="special_event" {{ old('type', $holiday->type) === 'special_event' ? 'selected' : '' }}>مناسبة خاصة</option>
 </select>
 @error('type')
 <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
 @enderror
 </div>

 {{-- Date Range --}}
 <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
 <div>
 <label class="block text-sm font-medium text-gray-700 mb-2">
 تاريخ البداية <span class="text-red-600">*</span>
 </label>
 <input type="date" name="start_date" value="{{ old('start_date', $holiday->start_date->format('Y-m-d')) }}" required
 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 ">
 @error('start_date')
 <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
 @enderror
 </div>

 <div>
 <label class="block text-sm font-medium text-gray-700 mb-2">
 تاريخ النهاية <span class="text-red-600">*</span>
 </label>
 <input type="date" name="end_date" value="{{ old('end_date', $holiday->end_date->format('Y-m-d')) }}" required
 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 ">
 @error('end_date')
 <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
 @enderror
 </div>
 </div>

 {{-- Is Recurring --}}
 <div class="flex items-center gap-3">
 <input type="checkbox" name="is_recurring" id="is_recurring" value="1" {{ old('is_recurring', $holiday->is_recurring) ? 'checked' : '' }}
 class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 ">
 <label for="is_recurring" class="text-sm font-medium text-gray-700 ">
 إجازة متكررة سنوياً
 </label>
 </div>
 <p class="text-xs text-gray-500 -mt-4">
 مثال: عيد الفطر، عيد الأضحى (تتكرر كل عام في نفس التواريخ تقريباً)
 </p>

 {{-- Metadata --}}
 <div class="bg-gray-50 rounded-lg p-4">
 <h3 class="text-sm font-medium text-gray-700 mb-2">معلومات إضافية</h3>
 <div class="space-y-1 text-sm text-gray-600 ">
 <p>تم الإنشاء بواسطة: {{ $holiday->creator?->name ?? 'غير محدد' }}</p>
 <p>تاريخ الإنشاء: {{ $holiday->created_at->format('Y-m-d H:i') }}</p>
 </div>
 </div>

 {{-- Actions --}}
 <div class="flex gap-3 pt-4 border-t border-gray-200">
 <x-button type="submit" variant="primary">
 حفظ التعديلات
 </x-button>
 <x-button href="{{ route('admin.holidays.index') }}" variant="ghost">
 إلغاء
 </x-button>
 </div>
 </form>
 </div>
</div>
@endsection
