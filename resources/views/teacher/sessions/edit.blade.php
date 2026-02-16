@extends('layouts.app')

@section('title', 'تعديل جلسة')

@section('content')
{{-- Page Header --}}
<div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-gradient-to-br from-white via-white to-slate-50 p-6 mb-6">
 <div class="absolute -top-12 -right-12 h-40 w-40 rounded-full bg-primary-100/70 blur-2xl"></div>
 <div class="absolute -bottom-16 -left-10 h-48 w-48 rounded-full bg-blue-100/60 blur-3xl"></div>
 <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
 <div>
 <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-50 text-primary-700 text-xs font-semibold border border-primary-100">
 <span class="h-2 w-2 rounded-full bg-primary-500"></span>
 تعديل الجلسة
 </div>
 <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-3">تعديل جلسة</h1>
 <p class="text-sm text-slate-600 mt-2">تحديث بيانات الجلسة المسجلة</p>
 </div>
 <x-button href="{{ route('teacher.sessions.index') }}" variant="ghost">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
 </svg>
 رجوع
 </x-button>
 </div>
</div>

<x-card>
 <x-card-body>
 <form method="POST" action="{{ route('teacher.sessions.update', $session) }}" class="space-y-6">
 @csrf
 @method('PUT')
 
 <div class="p-4 rounded-xl border border-slate-200 bg-slate-50">
 <div class="flex items-center gap-2 mb-4">
 <div class="h-2 w-2 rounded-full bg-primary-500"></div>
 <h3 class="font-bold text-slate-900">بيانات أساسية</h3>
 </div>

 <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
 {{-- Student --}}
 <x-select name="student_id" label="الطالب" required>
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
 </svg>
 </x-slot>
 @foreach ($students as $student)
 <option value="{{ $student->id }}" @if($session->student_id === $student->id) selected @endif>{{ $student->name }}</option>
 @endforeach
 </x-select>

 {{-- Date --}}
 <x-input name="date" label="التاريخ" type="date" value="{{ old('date', $session->date->toDateString()) }}" required>
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
 </svg>
 </x-slot>
 </x-input>

 {{-- Session Type --}}
 <x-select name="session_type" label="نوع الجلسة" required id="sessionType">
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
 </svg>
 </x-slot>
 <option value="new" @if($session->session_type === 'new') selected @endif>حفظ جديد</option>
 <option value="revision" @if($session->session_type === 'revision') selected @endif>مراجعة</option>
 <option value="foundation" @if($session->session_type === 'foundation') selected @endif>تأسيس</option>
 </x-select>
 </div>
 </div>

 {{-- Attendance --}}
 <div>
 <x-select name="attendance_status" label="حالة الحضور" required>
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </x-slot>
 <option value="present" @if($session->attendance_status === 'present') selected @endif>حاضر</option>
 <option value="absent" @if($session->attendance_status === 'absent') selected @endif>غائب</option>
 <option value="excused" @if($session->attendance_status === 'excused') selected @endif>معتذر</option>
 </x-select>
 </div>

 {{-- Memorization Fields (for new/revision) --}}
 <div id="memorizationFields" class="grid grid-cols-1 md:grid-cols-4 gap-6 p-4 rounded-xl border border-slate-200 bg-white">
 {{-- Surah --}}
 <x-select name="surah_id" label="السورة">
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
 </svg>
 </x-slot>
  <option value="">اختر السورة</option>
  @foreach ($surahs as $surah)
  <option value="{{ $surah->id }}" @if($session->surah_id === $surah->id) selected @endif>{{ $surah->id }} - {{ $surah->name_arabic }}</option>
  @endforeach
 </x-select>

 {{-- Ayah From --}}
 <x-input name="ayah_from" label="من آية" type="number" min="1" value="{{ old('ayah_from', $session->ayah_from) }}">
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
 </svg>
 </x-slot>
 </x-input>

 {{-- Ayah To --}}
 <x-input name="ayah_to" label="إلى آية" type="number" min="1" value="{{ old('ayah_to', $session->ayah_to) }}">
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
 </svg>
 </x-slot>
 </x-input>
 </div>

 {{-- Foundation Fields (for foundation) --}}
 <div id="foundationFields" class="hidden grid grid-cols-1 gap-4 p-4 rounded-xl border border-amber-200 bg-amber-50">
 @php
     $sessionSkillIds = $session->foundationSkills->pluck('id')->all();
     $sessionMastery = $session->foundationSkills->mapWithKeys(function ($skill) {
         return [$skill->id => $skill->pivot->mastery_percent];
     })->all();
     $fallbackSkillIds = $session->foundation_skill_id ? [$session->foundation_skill_id] : [];
     $fallbackMastery = $session->foundation_skill_id ? [$session->foundation_skill_id => ($session->mastery_progress ?? 0)] : [];
     $selectedSkillIds = collect(old('foundation_skill_ids', $sessionSkillIds ?: $fallbackSkillIds));
     $selectedMastery = old('foundation_mastery', $sessionMastery ?: $fallbackMastery);
 @endphp
 <div class="flex items-center justify-between">
 <div>
 <p class="font-semibold text-slate-900">المهارات التأسيسية</p>
 <p class="text-xs text-amber-700 mt-1">يمكن اختيار عدة مهارات وتحديد نسبة الإتقان لكل واحدة.</p>
 </div>
 <div class="text-xs text-amber-700">حدّث الإتقان بدقة</div>
 </div>
 <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
 @foreach ($foundationSkills as $skill)
 @php
     $isChecked = $selectedSkillIds->contains($skill->id);
     $masteryValue = $selectedMastery[$skill->id] ?? 0;
 @endphp
 <label class="flex items-center justify-between gap-3 p-3 rounded-lg border border-amber-200 bg-white">
 <div class="flex items-center gap-3">
 <input type="checkbox" name="foundation_skill_ids[]" value="{{ $skill->id }}" class="h-4 w-4 text-amber-600 border-amber-300 rounded" data-skill-id="{{ $skill->id }}" @if($isChecked) checked @endif>
 <span class="text-sm font-semibold text-slate-900">{{ $skill->name }}</span>
 </div>
 <input
 type="number"
 name="foundation_mastery[{{ $skill->id }}]"
 min="0"
 max="100"
 class="w-20 text-center text-sm border border-amber-200 rounded-md px-2 py-1"
 value="{{ $masteryValue }}"
 data-mastery-input="{{ $skill->id }}"
 @if(!$isChecked) disabled @endif
 >
 </label>
 @endforeach
 </div>
 </div>

 {{-- Score (memorization only) --}}
 <div id="scoreSection">
 <label class="block text-sm font-semibold text-gray-700 mb-3">التقييم (0 - 10)</label>
 <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-lg border border-slate-200">
 <span class="text-sm font-bold text-gray-500">0</span>
 <input type="range" name="score" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-primary-600" min="0" max="10" step="1" value="{{ old('score', $session->score) }}" oninput="document.getElementById('scoreValue').textContent = this.value">
 <span class="text-sm font-bold text-gray-500">10</span>
 <div class="w-12 h-10 flex items-center justify-center bg-white border-2 border-primary-100 rounded-lg shadow-sm">
 <span id="scoreValue" class="text-lg font-bold text-primary-600">{{ old('score', $session->score) }}</span>
 </div>
 </div>
 <p class="text-xs text-slate-500 mt-2">التقييم يستخدم مع الحفظ والمراجعة فقط.</p>
 </div>

 {{-- Notes --}}
 <x-textarea name="notes" label="ملاحظات" rows="3" placeholder="أي ملاحظات إضافية...">
 {{ old('notes', $session->notes) }}
 </x-textarea>

 {{-- Form Actions --}}
 <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
 <x-button type="submit" variant="primary" size="lg">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
 </svg>
 تحديث الجلسة
 </x-button>
 <x-button href="{{ route('teacher.sessions.index') }}" variant="outline" size="lg">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
 </svg>
 إلغاء
 </x-button>
 </div>
 </form>
 </x-card-body>
</x-card>

<script>
document.addEventListener('DOMContentLoaded', function() {
 const sessionTypeSelect = document.getElementById('sessionType');
 const memorizationFields = document.getElementById('memorizationFields');
 const foundationFields = document.getElementById('foundationFields');
 const scoreSection = document.getElementById('scoreSection');

 function toggleFields() {
 const sessionType = sessionTypeSelect.value;

 if (sessionType === 'foundation') {
 memorizationFields.classList.add('hidden');
 foundationFields.classList.remove('hidden');
 if (scoreSection) scoreSection.classList.add('hidden');
 } else {
 memorizationFields.classList.remove('hidden');
 foundationFields.classList.add('hidden');
 if (scoreSection) scoreSection.classList.remove('hidden');
 }
 }

 sessionTypeSelect.addEventListener('change', toggleFields);
 if (foundationFields) {
 foundationFields.querySelectorAll('input[type="checkbox"][data-skill-id]').forEach(function(checkbox) {
 checkbox.addEventListener('change', function() {
 const skillId = this.getAttribute('data-skill-id');
 const masteryInput = foundationFields.querySelector(`input[data-mastery-input="${skillId}"]`);
 if (masteryInput) {
 masteryInput.disabled = !this.checked;
 if (!this.checked) masteryInput.value = 0;
 }
 });
 });
 }
 toggleFields(); // Initialize on page load
});
</script>
@endsection
