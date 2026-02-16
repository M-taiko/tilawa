@extends('layouts.app')

@section('title', 'تعديل موعد')

@section('content')
{{-- Page Header --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
 <div>
 <h1 class="text-3xl font-bold text-gray-900">تعديل موعد</h1>
 <p class="text-sm text-gray-600 mt-1">تحديث موعد الحلقة في الجدول الأسبوعي</p>
 </div>
 <x-button href="{{ route('admin.schedules.index') }}" variant="ghost">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
 </svg>
 رجوع
 </x-button>
</div>

<x-card>
 <x-card-body>
 <form method="POST" action="{{ route('admin.schedules.update', $schedule) }}" class="space-y-6">
 @csrf
 @method('PUT')

 {{-- Class Selection --}}
 <x-select name="class_id" label="الحلقة" required>
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
 </svg>
 </x-slot>
 @foreach ($classes as $class)
 <option value="{{ $class->id }}" @if($schedule->class_id === $class->id) selected @endif>
 {{ $class->name }}
 </option>
 @endforeach
 </x-select>

 <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
 {{-- Day of Week --}}
 <x-select name="day_of_week" label="اليوم" required>
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
 </svg>
 </x-slot>
 <option value="sunday" @if($schedule->day_of_week === 'sunday') selected @endif>الأحد</option>
 <option value="monday" @if($schedule->day_of_week === 'monday') selected @endif>الاثنين</option>
 <option value="tuesday" @if($schedule->day_of_week === 'tuesday') selected @endif>الثلاثاء</option>
 <option value="wednesday" @if($schedule->day_of_week === 'wednesday') selected @endif>الأربعاء</option>
 <option value="thursday" @if($schedule->day_of_week === 'thursday') selected @endif>الخميس</option>
 <option value="friday" @if($schedule->day_of_week === 'friday') selected @endif>الجمعة</option>
 <option value="saturday" @if($schedule->day_of_week === 'saturday') selected @endif>السبت</option>
 </x-select>

 {{-- Location --}}
 <x-input name="location" label="الموقع" value="{{ old('location', $schedule->location) }}">
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
 </svg>
 </x-slot>
 </x-input>
 </div>

 <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
 {{-- Start Time --}}
 <x-input name="start_time" label="وقت البداية" type="time" value="{{ old('start_time', \Carbon\Carbon::parse($schedule->start_time)->format('H:i')) }}" required>
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </x-slot>
 </x-input>

 {{-- End Time --}}
 <x-input name="end_time" label="وقت النهاية" type="time" value="{{ old('end_time', \Carbon\Carbon::parse($schedule->end_time)->format('H:i')) }}" required>
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </x-slot>
 </x-input>
 </div>

 {{-- Active Status --}}
 <div class="flex items-center gap-3">
 <input type="checkbox" name="is_active" id="is_active" class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500" @if($schedule->is_active) checked @endif>
 <label for="is_active" class="text-sm font-medium text-gray-700">الموعد نشط</label>
 </div>

 {{-- Form Actions --}}
 <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
 <x-button type="submit" variant="primary" size="lg">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
 </svg>
 تحديث الموعد
 </x-button>
 <x-button href="{{ route('admin.schedules.index') }}" variant="outline" size="lg">
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
