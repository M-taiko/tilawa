@extends('layouts.app')

@section('title', 'إضافة طالب')

@section('content')
{{-- Page Header --}}
<div class="mb-8">
 <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
 <div>
 <h1 class="text-3xl font-bold text-gray-900">إضافة طالب</h1>
 <p class="text-sm text-gray-500 mt-1">إضافة طالب جديد إلى المركز</p>
 </div>
 <x-button href="{{ route('admin.students.index') }}" variant="ghost">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
 </svg>
 رجوع
 </x-button>
 </div>
</div>

{{-- Student Form --}}
<x-card class="bg-white border border-gray-200 rounded-xl shadow-sm">
 <x-card-body class="p-6">
 <form method="POST" action="{{ route('admin.students.store') }}" class="space-y-8">
 @csrf

 {{-- Section 1: Personal Information --}}
 <div class="space-y-6">
 <div class="flex items-center gap-3 pb-3 border-b border-gray-200">
 <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50">
 <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
 </svg>
 </div>
 <div>
 <h2 class="text-lg font-bold text-gray-900">معلومات الطالب</h2>
 <p class="text-sm text-gray-500">البيانات الأساسية للطالب</p>
 </div>
 </div>

 {{-- Student Name --}}
 <div>
 <x-input
 name="name"
 label="اسم الطالب"
 type="text"
 placeholder="أدخل اسم الطالب"
 :value="old('name')"
 required
 :error="$errors->first('name')"
 >
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
 </svg>
 </x-slot>
 </x-input>
 </div>

 {{-- Group, Track & Join Date --}}
 <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
 <x-select
 name="group"
 label="المجموعة"
 required
 :error="$errors->first('group')"
 id="groupSelect"
 >
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
 </svg>
 </x-slot>
 <option value="">اختر المجموعة</option>
 <option value="men" {{ old('group') == 'men' ? 'selected' : '' }}>رجال</option>
 <option value="women" {{ old('group') == 'women' ? 'selected' : '' }}>نساء</option>
 <option value="kids" {{ old('group') == 'kids' ? 'selected' : '' }}>أطفال</option>
 </x-select>

 <x-select
 name="track"
 label="المسار"
 required
 :error="$errors->first('track')"
 id="trackSelect"
 >
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
 </svg>
 </x-slot>
 <option value="">اختر المسار</option>
 <option value="memorization" {{ old('track') == 'memorization' ? 'selected' : '' }}>حفظ</option>
 <option value="foundation" {{ old('track') == 'foundation' ? 'selected' : '' }}>تأسيس</option>
 </x-select>

 <x-input
 name="join_date"
 label="تاريخ الالتحاق"
 type="date"
 :value="old('join_date', date('Y-m-d'))"
 required
 :error="$errors->first('join_date')"
 >
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
 </svg>
 </x-slot>
 </x-input>
 </div>
 </div>

 {{-- Section 2: Contact Information --}}
 <div class="space-y-6" id="contactSection" style="display: none;">
 <div class="flex items-center gap-3 pb-3 border-b border-gray-200">
 <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50">
 <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
 </svg>
 </div>
 <div>
 <h2 class="text-lg font-bold text-gray-900">معلومات التواصل</h2>
 <p class="text-sm text-gray-500">أرقام الاتصال والتواصل</p>
 </div>
 </div>

 {{-- Parent Info (Only for Kids) --}}
 <div id="parentFields" class="grid grid-cols-1 md:grid-cols-2 gap-6" style="display: none;">
 <x-input
 name="parent_name"
 label="اسم ولي الأمر"
 type="text"
 placeholder="أدخل اسم ولي الأمر"
 :value="old('parent_name')"
 :error="$errors->first('parent_name')"
 >
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
 </svg>
 </x-slot>
 </x-input>

 <x-input
 name="parent_phone"
 label="هاتف ولي الأمر"
 type="text"
 placeholder="أدخل رقم الهاتف"
 :value="old('parent_phone')"
 :error="$errors->first('parent_phone')"
 >
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
 </svg>
 </x-slot>
 </x-input>
 </div>

 {{-- Student Phone (For Men/Women) - Optional --}}
 <div id="studentPhoneField" style="display: none;">
 <x-input
 name="student_phone"
 label="رقم هاتف الطالب (اختياري)"
 type="text"
 placeholder="أدخل رقم هاتف الطالب"
 :value="old('student_phone')"
 :error="$errors->first('student_phone')"
 id="studentPhoneInput"
 >
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
 </svg>
 </x-slot>
 </x-input>
 </div>
 </div>

 {{-- Section 3: Learning Progress --}}
 <div class="space-y-6" id="progressSection" style="display: none;">
 <div class="flex items-center gap-3 pb-3 border-b border-gray-200">
 <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50">
 <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
 </svg>
 </div>
 <div>
 <h2 class="text-lg font-bold text-gray-900">التقدم الدراسي</h2>
 <p class="text-sm text-gray-500">الموضع الحالي ومستوى المهارات</p>
 </div>
 </div>

 {{-- Current Position (For Memorization Track) --}}
 <div id="memorizationFields" class="grid grid-cols-1 md:grid-cols-2 gap-6" style="display: none;">
 <x-select
 name="current_surah_id"
 label="الموضع الحالي - السورة"
 :error="$errors->first('current_surah_id')"
 >
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
 </svg>
 </x-slot>
  <option value="">اختر السورة (اختياري)</option>
  @foreach($surahs as $surah)
  <option value="{{ $surah->id }}" {{ old('current_surah_id') == $surah->id ? 'selected' : '' }}>
  {{ $surah->name_arabic }}
  </option>
  @endforeach
 </x-select>

 <x-input
 name="current_ayah"
 label="رقم الآية"
 type="number"
 min="1"
 placeholder="رقم الآية الحالية"
 :value="old('current_ayah')"
 :error="$errors->first('current_ayah')"
 >
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
 </svg>
 </x-slot>
 </x-input>
 </div>

 {{-- Foundation Skills (For Foundation Track) --}}
 <div id="foundationFields" style="display: none;">
 <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
 <div class="flex items-center gap-3 mb-4">
 <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100">
 <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </div>
 <div>
 <h3 class="text-base font-bold text-gray-900">مهارات التأسيس</h3>
 <p class="text-sm text-gray-600">حدد مستوى إتقان كل مهارة (0-100%)</p>
 </div>
 </div>

 <div class="space-y-4">
 @foreach($foundationSkills as $skill)
 <div class="bg-white border border-gray-200 p-4 rounded-lg hover:border-blue-300 transition-colors">
 <div class="flex items-center justify-between mb-3">
 <label class="text-sm font-semibold text-gray-800">{{ $skill->name }}</label>
 <span class="px-3 py-1 rounded-full text-sm font-bold bg-blue-600 text-white">
 <span id="mastery-value-{{ $skill->id }}">{{ old('mastery.'.$skill->id, 0) }}</span>%
 </span>
 </div>
 <input
 type="range"
 name="mastery[{{ $skill->id }}]"
 min="0"
 max="100"
 step="5"
 value="{{ old('mastery.'.$skill->id, 0) }}"
 class="w-full h-3 bg-gray-200 rounded-lg appearance-none cursor-pointer slider"
 oninput="document.getElementById('mastery-value-{{ $skill->id }}').textContent = this.value"
 >
 <div class="flex justify-between text-xs font-medium text-gray-500 mt-2">
 <span>0%</span>
 <span>50%</span>
 <span>100%</span>
 </div>
 </div>
 @endforeach
 </div>
 </div>
 </div>
 </div>

 {{-- Section 4: Class Assignment --}}
 <div class="space-y-6" id="classSection" style="display: none;">
 <div class="flex items-center gap-3 pb-3 border-b border-gray-200">
 <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50">
 <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
 </svg>
 </div>
 <div>
 <h2 class="text-lg font-bold text-gray-900">تعيين الحلقة</h2>
 <p class="text-sm text-gray-500">الحلقة الدراسية للطالب</p>
 </div>
 </div>

 <div>
 <x-select
 name="class_id"
 label="الحلقة"
 :error="$errors->first('class_id')"
 id="classSelect"
 >
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
 </svg>
 </x-slot>
 <option value="">غير معيّن</option>
 @foreach ($classes as $class)
 @php
 $groupLabel = match($class->group) {
 'men' => 'رجال',
 'women' => 'نساء',
 'kids' => 'أطفال',
 default => $class->group
 };
 $trackLabel = match($class->track) {
 'memorization' => 'حفظ',
 'foundation' => 'تأسيس',
 default => $class->track
 };
 @endphp
 <option
 value="{{ $class->id }}"
 data-group="{{ $class->group }}"
 data-track="{{ $class->track }}"
 {{ old('class_id') == $class->id ? 'selected' : '' }}
 >
 {{ $class->name }} ({{ $groupLabel }} - {{ $trackLabel }})
 </option>
 @endforeach
 </x-select>
 <p class="text-xs text-gray-500 mt-2">
 <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 سيتم عرض الحلقات المتوافقة مع المجموعة والمسار المختارين فقط
 </p>
 </div>
 </div>

 {{-- Form Actions --}}
 <div class="flex flex-col sm:flex-row items-center gap-3 pt-6 border-t border-gray-200">
 <x-button type="submit" variant="primary" size="lg" class="w-full sm:w-auto">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
 </svg>
 حفظ الطالب
 </x-button>
 <x-button href="{{ route('admin.students.index') }}" variant="outline" size="lg" class="w-full sm:w-auto">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
 </svg>
 إلغاء
 </x-button>
 </div>
 </form>
 </x-card-body>
</x-card>

<style>
/* Clean slider styling */
input[type="range"].slider {
 background: transparent;
}

input[type="range"].slider::-webkit-slider-thumb {
 appearance: none;
 width: 20px;
 height: 20px;
 border-radius: 50%;
 background: #2563eb;
 cursor: pointer;
 border: 3px solid white;
 box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
 transition: all 0.2s ease;
}

input[type="range"].slider::-webkit-slider-thumb:hover {
 background: #1d4ed8;
 transform: scale(1.1);
}

input[type="range"].slider::-moz-range-thumb {
 width: 20px;
 height: 20px;
 border-radius: 50%;
 background: #2563eb;
 cursor: pointer;
 border: 3px solid white;
 box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
 transition: all 0.2s ease;
}

input[type="range"].slider::-moz-range-thumb:hover {
 background: #1d4ed8;
 transform: scale(1.1);
}

input[type="range"].slider::-webkit-slider-runnable-track {
 height: 8px;
 border-radius: 4px;
 background: linear-gradient(to right, #ef4444 0%, #f59e0b 35%, #84cc16 65%, #10b981 100%);
 box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
}

input[type="range"].slider::-moz-range-track {
 height: 8px;
 border-radius: 4px;
 background: linear-gradient(to right, #ef4444 0%, #f59e0b 35%, #84cc16 65%, #10b981 100%);
 box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
 // Use name attribute to find selects (more reliable with x-select components)
 const groupSelect = document.querySelector('select[name="group"]');
 const trackSelect = document.querySelector('select[name="track"]');
 const classSelect = document.querySelector('select[name="class_id"]');
 const contactSection = document.getElementById('contactSection');
 const progressSection = document.getElementById('progressSection');
 const classSection = document.getElementById('classSection');
 const parentFields = document.getElementById('parentFields');
 const studentPhoneField = document.getElementById('studentPhoneField');
 const memorizationFields = document.getElementById('memorizationFields');
 const foundationFields = document.getElementById('foundationFields');

 function toggleFields() {
 if (!groupSelect || !trackSelect) return;

 const group = groupSelect.value;
 const track = trackSelect.value;
 const isKids = group === 'kids';
 const isMenOrWomen = group === 'men' || group === 'women';
 const isMemorization = track === 'memorization';
 const isFoundation = track === 'foundation';

 // Show/hide entire sections based on selections
 // Contact section: show when group is selected
 if (contactSection) {
 contactSection.style.display = group ? 'block' : 'none';
 }

 // Progress section: show when track is selected
 if (progressSection) {
 progressSection.style.display = track ? 'block' : 'none';
 }

 // Class section: show when both group and track are selected
 if (classSection) {
 classSection.style.display = (group && track) ? 'block' : 'none';
 }

 // Parent fields (only for kids - required)
 if (parentFields) {
 parentFields.style.display = isKids ? 'grid' : 'none';
 const parentNameInput = parentFields.querySelector('input[name="parent_name"]');
 const parentPhoneInput = parentFields.querySelector('input[name="parent_phone"]');
 if (parentNameInput) parentNameInput.required = isKids;
 if (parentPhoneInput) parentPhoneInput.required = isKids;
 }

 // Student phone (only for men/women - optional)
 if (studentPhoneField) {
 studentPhoneField.style.display = isMenOrWomen ? 'block' : 'none';
 }

 // Memorization fields (current position)
 if (memorizationFields) {
 memorizationFields.style.display = isMemorization ? 'grid' : 'none';
 }

 // Foundation skills
 if (foundationFields) {
 foundationFields.style.display = isFoundation ? 'block' : 'none';
 }

 // Filter class options based on group and track
 filterClassOptions();
 }

 function filterClassOptions() {
 if (!classSelect) return;

 const group = groupSelect ? groupSelect.value : '';
 const track = trackSelect ? trackSelect.value : '';
 const currentValue = classSelect.value;
 let hasMatchingOption = false;

 // Get all options except the first one (empty option)
 const options = classSelect.querySelectorAll('option');

 options.forEach((option, index) => {
 // Skip the "غير معيّن" option
 if (index === 0) return;

 const optionGroup = option.getAttribute('data-group');
 const optionTrack = option.getAttribute('data-track');

 // Show option only if both group and track match (or are not selected)
 const groupMatch = !group || optionGroup === group;
 const trackMatch = !track || optionTrack === track;
 const shouldShow = groupMatch && trackMatch;

 if (shouldShow) {
 option.style.display = '';
 option.disabled = false;
 if (option.value === currentValue) {
 hasMatchingOption = true;
 }
 } else {
 option.style.display = 'none';
 option.disabled = true;
 }
 });

 // If current selection doesn't match filters, reset to empty
 if (currentValue && !hasMatchingOption) {
 classSelect.value = '';
 }
 }

 // Add event listeners
 if (groupSelect) {
 groupSelect.addEventListener('change', toggleFields);
 }
 if (trackSelect) {
 trackSelect.addEventListener('change', toggleFields);
 }

 // Initial check on page load
 toggleFields();
});
</script>
@endsection
