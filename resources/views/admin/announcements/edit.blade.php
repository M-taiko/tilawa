@extends('layouts.app')

@section('title', 'تعديل إعلان')

@section('content')
<div class="max-w-3xl mx-auto">
 <div class="mb-8">
 <h1 class="text-3xl font-bold text-gray-900">تعديل إعلان</h1>
 <p class="text-sm text-gray-500 mt-1">تحديث معلومات الإعلان</p>
 </div>

 <div class="bg-white border border-gray-200 rounded-lg p-6">
 <form action="{{ route('admin.announcements.update', $announcement) }}" method="POST" class="space-y-6">
 @csrf
 @method('PUT')

 {{-- Title --}}
 <div>
 <label class="block text-sm font-medium text-gray-700 mb-2">
 العنوان <span class="text-red-600">*</span>
 </label>
 <input type="text" name="title" value="{{ old('title', $announcement->title) }}" required
 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
 @error('title')
 <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
 @enderror
 </div>

 {{-- Content --}}
 <div>
 <label class="block text-sm font-medium text-gray-700 mb-2">
 المحتوى <span class="text-red-600">*</span>
 </label>
 <textarea name="content" rows="5" required
 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('content', $announcement->content) }}</textarea>
 @error('content')
 <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
 @enderror
 </div>

 {{-- Target Audience --}}
 <div>
 <label class="block text-sm font-medium text-gray-700 mb-2">
 الجمهور المستهدف <span class="text-red-600">*</span>
 </label>
 <select name="target_audience" required
 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
 <option value="all" {{ old('target_audience', $announcement->target_audience) === 'all' ? 'selected' : '' }}>الكل</option>
 <option value="teachers" {{ old('target_audience', $announcement->target_audience) === 'teachers' ? 'selected' : '' }}>المعلمين فقط</option>
 <option value="students" {{ old('target_audience', $announcement->target_audience) === 'students' ? 'selected' : '' }}>الطلاب فقط</option>
 </select>
 @error('target_audience')
 <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
 @enderror
 </div>

 {{-- Priority --}}
 <div>
 <label class="block text-sm font-medium text-gray-700 mb-2">
 الأولوية <span class="text-red-600">*</span>
 </label>
 <select name="priority" required
 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
 <option value="normal" {{ old('priority', $announcement->priority) === 'normal' ? 'selected' : '' }}>عادي</option>
 <option value="high" {{ old('priority', $announcement->priority) === 'high' ? 'selected' : '' }}>مهم</option>
 <option value="urgent" {{ old('priority', $announcement->priority) === 'urgent' ? 'selected' : '' }}>عاجل</option>
 <option value="low" {{ old('priority', $announcement->priority) === 'low' ? 'selected' : '' }}>منخفض</option>
 </select>
 @error('priority')
 <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
 @enderror
 </div>

 {{-- Expires At --}}
 <div>
 <label class="block text-sm font-medium text-gray-700 mb-2">
 تاريخ الانتهاء (اختياري)
 </label>
 <input type="date" name="expires_at" value="{{ old('expires_at', $announcement->expires_at?->format('Y-m-d')) }}"
 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
 <p class="mt-1 text-sm text-gray-500">اترك فارغاً إذا كان الإعلان دائماً</p>
 @error('expires_at')
 <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
 @enderror
 </div>

 {{-- Is Active --}}
 <div class="flex items-center gap-3">
 <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $announcement->is_active) ? 'checked' : '' }}
 class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
 <label for="is_active" class="text-sm font-medium text-gray-700">
 نشط (ظاهر للمستخدمين)
 </label>
 </div>

 {{-- Metadata --}}
 <div class="bg-gray-50 rounded-lg p-4">
 <h3 class="text-sm font-medium text-gray-700 mb-2">معلومات إضافية</h3>
 <div class="space-y-1 text-sm text-gray-600">
 <p>تم الإنشاء بواسطة: {{ $announcement->creator?->name ?? 'غير محدد' }}</p>
 <p>تاريخ الإنشاء: {{ $announcement->created_at->format('Y-m-d H:i') }}</p>
 </div>
 </div>

 {{-- Actions --}}
 <div class="flex gap-3 pt-4 border-t border-gray-200">
 <x-button type="submit" variant="primary">
 حفظ التعديلات
 </x-button>
 <x-button href="{{ route('admin.announcements.index') }}" variant="ghost">
 إلغاء
 </x-button>
 </div>
 </form>
 </div>
</div>
@endsection
