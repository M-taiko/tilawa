@extends('layouts.app')

@section('title', 'تعديل طالب')

@section('content')
{{-- Page Header --}}
<div class="mb-8">
 <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
 <div>
 <h1 class="text-3xl font-bold text-gray-900">تعديل طالب</h1>
 <p class="text-sm text-gray-500 mt-1">تحديث بيانات الطالب <span class="font-semibold text-gray-900">{{ $student->name }}</span></p>
 </div>
 <x-button href="{{ route('admin.students.index') }}" variant="ghost">
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
 <form method="POST" action="{{ route('admin.students.update', $student) }}" class="space-y-8">
 @csrf
 @method('PUT')

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
 :value="old('name', $student->name)"
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
  >
  <x-slot name="icon">
  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
  </svg>
  </x-slot>
  <option value="">اختر المجموعة</option>
  <option value="men" @if(old('group', $student->group) === 'men') selected @endif>رجال</option>
  <option value="women" @if(old('group', $student->group) === 'women') selected @endif>نساء</option>
  <option value="kids" @if(old('group', $student->group) === 'kids') selected @endif>أطفال</option>
  </x-select>

  <x-select
  name="track"
  label="المسار"
  required
  :error="$errors->first('track')"
  >
  <x-slot name="icon">
  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
  </svg>
  </x-slot>
  <option value="">اختر المسار</option>
  <option value="memorization" @if(old('track', $student->track) === 'memorization') selected @endif>حفظ</option>
  <option value="foundation" @if(old('track', $student->track) === 'foundation') selected @endif>تأسيس</option>
  </x-select>

  <x-input
  name="join_date"
  label="تاريخ الالتحاق"
  type="date"
  :value="old('join_date', $student->join_date?->format('Y-m-d'))"
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
 <div class="space-y-6" id="contactSection" style="display: {{ old('group', $student->group) ? 'block' : 'none' }};">
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
 <div id="parentFields" class="grid grid-cols-1 md:grid-cols-2 gap-6" style="display: {{ old('group', $student->group) === 'kids' ? 'grid' : 'none' }};">
 <x-input
 name="parent_name"
 label="اسم ولي الأمر"
 type="text"
 placeholder="أدخل اسم ولي الأمر"
 :value="old('parent_name', $student->parent_name)"
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
 :value="old('parent_phone', $student->parent_phone)"
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
 <div id="studentPhoneField" style="display: {{ in_array(old('group', $student->group), ['men', 'women']) ? 'block' : 'none' }};">
 <x-input
 name="student_phone"
 label="رقم هاتف الطالب (اختياري)"
 type="text"
 placeholder="أدخل رقم الهاتف"
 :value="old('student_phone', $student->student_phone)"
 :error="$errors->first('student_phone')"
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
 <div class="space-y-6" id="progressSection" style="display: {{ old('track', $student->track) === 'memorization' ? 'block' : 'none' }};">
 <div class="flex items-center gap-3 pb-3 border-b border-gray-200">
 <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-50">
 <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
 </svg>
 </div>
 <div>
 <h2 class="text-lg font-bold text-gray-900">التقدم الدراسي</h2>
 <p class="text-sm text-gray-500">الموضع الحالي للطالب</p>
 </div>
 </div>

 {{-- Current Position (For Memorization Track) --}}
  <div id="memorizationFields" class="grid grid-cols-1 md:grid-cols-2 gap-6" style="display: {{ old('track', $student->track) === 'memorization' ? 'grid' : 'none' }};">
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
  <option value="{{ $surah->id }}" @if(old('current_surah_id', $student->current_surah_id) == $surah->id) selected @endif>
  {{ $surah->name_arabic }}
  </option>
  @endforeach
  </x-select>

  <x-input
  name="current_ayah"
  label="رقم الآية"
  type="number"
  min="1"
  placeholder="اختياري"
  :value="old('current_ayah', $student->current_ayah)"
  :error="$errors->first('current_ayah')"
  >
  <x-slot name="icon">
  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
  </svg>
  </x-slot>
  </x-input>
  </div>
 </div>

 {{-- Section 4: Class Assignment --}}
 <div class="space-y-6" id="classSection" style="display: {{ (old('group', $student->group) && old('track', $student->track)) ? 'block' : 'none' }};">
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
  @if($student->class_id === $class->id) selected @endif
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
 تحديث البيانات
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
 </div>

 {{-- Sidebar --}}
 <div class="lg:col-span-1 space-y-6">
 @if($student->parent_phone)
 <x-card class="bg-white border border-gray-200 rounded-xl shadow-sm">
 <x-card-header class="bg-gray-50 border-b border-gray-200">
 <div class="flex items-center gap-3">
 <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-100">
 <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
 </svg>
 </div>
 <div>
 <h3 class="font-bold text-gray-900">رمز ولي الأمر</h3>
 <p class="text-xs text-gray-600">للوصول إلى صفحة ولي الأمر</p>
 </div>
 </div>
 </x-card-header>
 <x-card-body class="space-y-4">
 @php
 $parentUrl = url("/p/{$student->parent_portal_token}");
 $parentPhone = preg_replace('/\D+/', '', $student->parent_phone ?? '');
 $waMessage = "السلام عليكم ورحمة الله وبركاته\n\n";
 $waMessage .= "نشكركم على ثقتكم بمركز تحفيظ القرآن 🌟\n\n";
 $waMessage .= "يسعدنا إرسال رابط متابعة الطالب/ة: *{$student->name}*\n\n";
 $waMessage .= "من خلال هذا الرابط يمكنكم:\n";
 $waMessage .= "✅ متابعة التقدم اليومي في الحفظ\n";
 $waMessage .= "✅ الاطلاع على التقييمات والملاحظات\n";
 $waMessage .= "✅ مراجعة سجل الحضور والغياب\n";
 $waMessage .= "✅ متابعة الإحصائيات والرسوم البيانية\n\n";
 $waMessage .= "🔗 رابط المتابعة:\n{$parentUrl}\n\n";
 $waMessage .= "_احفظ هذا الرابط للرجوع إليه في أي وقت_\n\n";
 $waMessage .= "للاستفسارات، نحن في خدمتكم دائماً 🤝";
 $waText = urlencode($waMessage);
 $waLink = $parentPhone ? "https://wa.me/{$parentPhone}?text={$waText}" : null;
 @endphp

 {{-- Token Display --}}
 <div>
 <label class="block text-xs font-semibold text-gray-700 mb-2">الرمز</label>
 <div class="flex items-center gap-2 p-3 rounded-lg bg-gray-50 border border-gray-200">
 <code class="flex-1 text-sm font-mono text-gray-900 break-all">{{ $student->parent_portal_token }}</code>
 <button
 type="button"
 onclick="navigator.clipboard.writeText('{{ $student->parent_portal_token }}')"
 class="p-1.5 rounded-lg hover:bg-gray-200 text-gray-600 transition-colors flex-shrink-0"
 title="نسخ الرمز"
 >
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
 </svg>
 </button>
 </div>
 </div>

 {{-- Parent Link --}}
 <div>
 <x-button href="{{ $parentUrl }}" variant="outline" class="w-full justify-center" target="_blank" rel="noopener">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
 </svg>
 فتح رابط ولي الأمر
 </x-button>
 </div>

 {{-- WhatsApp Share --}}
 @if($waLink)
 <div>
 <x-button href="{{ $waLink }}" variant="success" class="w-full justify-center" target="_blank" rel="noopener">
 <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
 <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
 </svg>
 إرسال عبر واتساب
 </x-button>
 </div>
 @endif

 {{-- Regenerate Token --}}
 <div class="pt-4 border-t border-gray-200">
 <form method="POST" action="{{ route('admin.students.regenerate-token', $student) }}">
 @csrf
 <x-button type="submit" variant="warning" class="w-full justify-center" onclick="return confirm('هل أنت متأكد من إعادة توليد الرمز؟ الرمز القديم لن يعمل بعد ذلك.')">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
 </svg>
 إعادة توليد الرمز
 </x-button>
 </form>
 </div>
 </x-card-body>
 </x-card>
 @endif

 {{-- Foundation Skills Mastery Card (only for foundation track) --}}
 @if($student->track === 'foundation' && $foundationSkills->isNotEmpty())
 <x-card class="bg-white border border-gray-200 rounded-xl shadow-sm">
 <x-card-header class="bg-gray-50 border-b border-gray-200">
 <div class="flex items-center gap-3">
 <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-100">
 <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </div>
 <div>
 <h3 class="font-bold text-gray-900">مهارات التأسيس</h3>
 <p class="text-xs text-gray-600">إدارة نسب الإتقان</p>
 </div>
 </div>
 </x-card-header>
 <x-card-body>
 <form method="POST" action="{{ route('admin.students.update-mastery', $student) }}" class="space-y-4">
 @csrf

 @foreach($foundationSkills as $skill)
 @php
 $currentMastery = $masteryMap[$skill->id] ?? 0;
 @endphp
 <div class="bg-white border border-gray-200 p-4 rounded-lg hover:border-blue-300 transition-colors">
 <div class="flex items-center justify-between mb-3">
 <label class="text-sm font-semibold text-gray-800">{{ $skill->name }}</label>
 <span class="px-3 py-1 rounded-full text-sm font-bold bg-blue-600 text-white">
 <span id="mastery-value-{{ $skill->id }}">{{ $currentMastery }}</span>%
 </span>
 </div>
 <input
 type="range"
 name="mastery[{{ $skill->id }}]"
 min="0"
 max="100"
 step="5"
 value="{{ old('mastery.'.$skill->id, $currentMastery) }}"
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

 <div class="pt-4 border-t border-gray-200">
 <x-button type="submit" variant="primary" class="w-full justify-center">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
 </svg>
 حفظ نسب الإتقان
 </x-button>
 </div>
 </form>
 </x-card-body>
 </x-card>
 @endif

 {{-- Student Status Management --}}
 <x-card class="bg-white border border-gray-200 rounded-xl shadow-sm">
 <x-card-header class="bg-gray-50 border-b border-gray-200">
 <div class="flex items-center gap-3">
 <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-blue-100">
 <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </div>
 <div>
 <h3 class="font-bold text-gray-900">حالة الطالب</h3>
 <p class="text-xs text-gray-600">إدارة حالة الطالب</p>
 </div>
 </div>
 </x-card-header>
 <x-card-body class="space-y-4">
 {{-- Current Status Badge --}}
 <div>
 <label class="block text-xs font-semibold text-gray-700 mb-2">الحالة الحالية</label>
 <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold
 @if($student->status === 'active') bg-green-600 text-white
 @elseif($student->status === 'graduated') bg-blue-600 text-white
 @else bg-red-600 text-white @endif">
 @if($student->status === 'active')
 نشط
 @elseif($student->status === 'graduated')
 خريج ({{ $student->graduation_date?->format('Y-m-d') }})
 @else
 غير نشط
 @endif
 </div>
 </div>

 {{-- Graduate Student Form (Only for Active Students) --}}
 @if($student->status === 'active')
 <div class="border-t border-gray-200 pt-4">
 <h4 class="text-sm font-semibold text-gray-900 mb-3">تخريج الطالب</h4>
 <form method="POST" action="{{ route('admin.students.graduate', $student) }}"
 onsubmit="return confirm('هل أنت متأكد من تخريج هذا الطالب؟ سيتم إزالته من الحلقة.')">
 @csrf
 <div class="space-y-3">
 <x-input
 name="graduation_date"
 type="date"
 label="تاريخ التخرج"
 :value="date('Y-m-d')"
 required
 :error="$errors->first('graduation_date')"
 >
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
 </svg>
 </x-slot>
 </x-input>
 <x-button type="submit" variant="success" class="w-full justify-center">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
 </svg>
 تخريج الطالب
 </x-button>
 </div>
 </form>
 </div>
 @endif

 {{-- Toggle Status Button --}}
 @if($student->status !== 'graduated')
 <div class="border-t border-gray-200 pt-4">
 <form method="POST" action="{{ route('admin.students.toggle-status', $student) }}">
 @csrf
 <x-button
 type="submit"
 variant="{{ $student->status === 'active' ? 'warning' : 'primary' }}"
 class="w-full justify-center"
 >
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 @if($student->status === 'active')
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
 @else
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 @endif
 </svg>
 {{ $student->status === 'active' ? 'تعطيل الطالب' : 'تفعيل الطالب' }}
 </x-button>
 </form>
 </div>
 @endif
 </x-card-body>
 </x-card>
 </div>
</div>

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
 // Find form elements
 const groupSelect = document.querySelector('select[name="group"]');
 const trackSelect = document.querySelector('select[name="track"]');
 const classSelect = document.querySelector('select[name="class_id"]');
 const contactSection = document.getElementById('contactSection');
 const progressSection = document.getElementById('progressSection');
 const classSection = document.getElementById('classSection');
 const parentFields = document.getElementById('parentFields');
 const studentPhoneField = document.getElementById('studentPhoneField');
 const memorizationFields = document.getElementById('memorizationFields');

 function toggleFields() {
 if (!groupSelect || !trackSelect) return;

 const group = groupSelect.value;
 const track = trackSelect.value;
 const isKids = group === 'kids';
 const isMenOrWomen = group === 'men' || group === 'women';
 const isMemorization = track === 'memorization';

 // Show/hide entire sections based on selections
 // Contact section: show when group is selected
 if (contactSection) {
 contactSection.style.display = group ? 'block' : 'none';
 }

 // Progress section: show when track is memorization (foundation is in sidebar for edit)
 if (progressSection) {
 progressSection.style.display = isMemorization ? 'block' : 'none';
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
