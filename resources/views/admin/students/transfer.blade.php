@extends('layouts.app')

@section('title', 'نقل طالب')

@section('content')
<div class="max-w-3xl mx-auto">
 <div class="mb-8">
 <h1 class="text-3xl font-bold text-gray-900 ">نقل طالب</h1>
 <p class="text-sm text-gray-500 mt-1">نقل {{ $student->name }} إلى حلقة أخرى</p>
 </div>

 {{-- Current Info --}}
 <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
 <h3 class="text-lg font-semibold text-blue-900 mb-4">المعلومات الحالية</h3>
 <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
 <div>
 <p class="text-blue-700 font-medium">الحلقة الحالية:</p>
 <p class="text-blue-900 ">
 {{ $student->class ? $student->class->name : 'غير معيّن لحلقة' }}
 </p>
 </div>
 <div>
 <p class="text-blue-700 font-medium">المعلم الحالي:</p>
 <p class="text-blue-900 ">
 {{ $student->class?->teacher?->name ?? 'غير معيّن' }}
 </p>
 </div>
 <div>
 <p class="text-blue-700 font-medium">الفئة:</p>
 <p class="text-blue-900 ">
 {{ $student->group === 'male' ? 'طلاب' : 'طالبات' }}
 </p>
 </div>
 <div>
 <p class="text-blue-700 font-medium">المسار:</p>
 <p class="text-blue-900 ">
 {{ $student->track === 'memorization' ? 'حفظ' : 'تأسيس' }}
 </p>
 </div>
 </div>
 </div>

 {{-- Transfer Form --}}
 <div class="bg-white border border-gray-200 rounded-lg p-6">
 <form action="{{ route('admin.students.transfer.process', $student) }}" method="POST" class="space-y-6">
 @csrf

 {{-- New Class --}}
 <div>
 <label class="block text-sm font-medium text-gray-700 mb-2">
 الحلقة الجديدة
 </label>
 <select name="to_class_id"
 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 ">
 <option value="">بدون حلقة (إزالة من الحلقة الحالية)</option>
 @foreach($classes as $class)
 @if($class->id !== $student->class_id)
 <option value="{{ $class->id }}" {{ old('to_class_id') == $class->id ? 'selected' : '' }}>
 {{ $class->name }} - {{ $class->teacher?->name ?? 'بدون معلم' }}
 ({{ $class->group === 'male' ? 'طلاب' : 'طالبات' }})
 </option>
 @endif
 @endforeach
 </select>
 <p class="mt-1 text-xs text-gray-500 ">
 اختر الحلقة الجديدة، أو اترك فارغاً لإزالة الطالب من الحلقة الحالية فقط
 </p>
 @error('to_class_id')
 <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
 @enderror
 </div>

 {{-- Reason --}}
 <div>
 <label class="block text-sm font-medium text-gray-700 mb-2">
 سبب النقل <span class="text-red-600">*</span>
 </label>
 <select name="reason" required
 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 "
 onchange="if(this.value === 'other') { document.getElementById('custom-reason').classList.remove('hidden'); } else { document.getElementById('custom-reason').classList.add('hidden'); }">
 <option value="">اختر السبب</option>
 <option value="level_change" {{ old('reason') === 'level_change' ? 'selected' : '' }}>تغيير المستوى</option>
 <option value="teacher_preference" {{ old('reason') === 'teacher_preference' ? 'selected' : '' }}>طلب ولي الأمر للمعلم</option>
 <option value="schedule_conflict" {{ old('reason') === 'schedule_conflict' ? 'selected' : '' }}>تعارض في الجدول</option>
 <option value="class_size" {{ old('reason') === 'class_size' ? 'selected' : '' }}>إعادة توزيع الأعداد</option>
 <option value="behavioral_issues" {{ old('reason') === 'behavioral_issues' ? 'selected' : '' }}>مشاكل سلوكية</option>
 <option value="other" {{ old('reason') === 'other' ? 'selected' : '' }}>سبب آخر</option>
 </select>
 @error('reason')
 <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
 @enderror

 <input type="text" id="custom-reason" placeholder="اكتب السبب..."
 class="hidden mt-2 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 ">
 </div>

 {{-- Notes --}}
 <div>
 <label class="block text-sm font-medium text-gray-700 mb-2">
 ملاحظات إضافية (اختياري)
 </label>
 <textarea name="notes" rows="4"
 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 ">{{ old('notes') }}</textarea>
 @error('notes')
 <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
 @enderror
 </div>

 {{-- Warning --}}
 <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
 <div class="flex items-start gap-3">
 <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
 </svg>
 <div class="text-sm text-yellow-800 ">
 <p class="font-medium mb-1">تنبيه:</p>
 <ul class="list-disc list-inside space-y-1">
 <li>سيتم تسجيل هذا النقل في سجل التحويلات</li>
 <li>سيتم إشعار المعلم الحالي والجديد بعملية النقل</li>
 <li>تأكد من صحة البيانات قبل المتابعة</li>
 </ul>
 </div>
 </div>
 </div>

 {{-- Actions --}}
 <div class="flex gap-3 pt-4 border-t border-gray-200 ">
 <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
 تأكيد النقل
 </button>
 <a href="{{ route('admin.students.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 :bg-gray-600 font-medium">
 إلغاء
 </a>
 <a href="{{ route('admin.students.transfer.history', $student) }}" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 :bg-gray-500 font-medium">
 عرض سجل التحويلات
 </a>
 </div>
 </form>
 </div>
</div>

<script>
// Handle custom reason field
document.addEventListener('DOMContentLoaded', function() {
 const reasonSelect = document.querySelector('select[name="reason"]');
 const customReasonInput = document.getElementById('custom-reason');

 if (reasonSelect.value === 'other') {
 customReasonInput.classList.remove('hidden');
 }

 customReasonInput.addEventListener('input', function() {
 if (this.value) {
 reasonSelect.value = this.value;
 }
 });
});
</script>
@endsection
