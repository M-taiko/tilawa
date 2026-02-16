@extends('layouts.app')

@section('title', 'تقويم الإجازات')

@section('content')
<div class="max-w-7xl mx-auto">
 {{-- Page Header --}}
 <div class="flex items-center justify-between mb-8">
 <div>
 <h1 class="text-3xl font-bold text-gray-900 ">تقويم الإجازات</h1>
 <p class="text-sm text-gray-500 mt-1">عرض الإجازات والعطلات في التقويم</p>
 </div>
 <div class="flex gap-2">
 <a href="{{ route('admin.holidays.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 :bg-gray-600">
 عرض القائمة
 </a>
 <x-button href="{{ route('admin.holidays.create') }}" variant="primary">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
 </svg>
 إضافة إجازة
 </x-button>
 </div>
 </div>

 {{-- Month/Year Navigation --}}
 <div class="bg-white border border-gray-200 rounded-lg p-4 mb-6">
 <div class="flex items-center justify-between">
 <a href="{{ route('admin.holidays.calendar', ['year' => $month == 1 ? $year - 1 : $year, 'month' => $month == 1 ? 12 : $month - 1]) }}"
 class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 :bg-gray-600">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
 </svg>
 </a>

 <h2 class="text-2xl font-bold text-gray-900 ">
 {{ \Carbon\Carbon::create($year, $month)->locale('ar')->translatedFormat('F Y') }}
 </h2>

 <a href="{{ route('admin.holidays.calendar', ['year' => $month == 12 ? $year + 1 : $year, 'month' => $month == 12 ? 1 : $month + 1]) }}"
 class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 :bg-gray-600">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
 </svg>
 </a>
 </div>
 </div>

 {{-- Calendar Grid --}}
 <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
 {{-- Day Headers --}}
 <div class="grid grid-cols-7 bg-gray-50 border-b border-gray-200 ">
 @foreach(['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'] as $day)
 <div class="p-3 text-center text-sm font-semibold text-gray-700 ">
 {{ $day }}
 </div>
 @endforeach
 </div>

 {{-- Calendar Days --}}
 @php
 $firstDay = \Carbon\Carbon::create($year, $month, 1);
 $daysInMonth = $firstDay->daysInMonth;
 $startDayOfWeek = $firstDay->dayOfWeek; // 0 = Sunday
 $today = now()->toDateString();
 @endphp

 <div class="grid grid-cols-7">
 {{-- Empty cells before first day --}}
 @for($i = 0; $i < $startDayOfWeek; $i++)
 <div class="min-h-[120px] p-2 bg-gray-50 border border-gray-200 "></div>
 @endfor

 {{-- Days of the month --}}
 @for($day = 1; $day <= $daysInMonth; $day++)
 @php
 $currentDate = \Carbon\Carbon::create($year, $month, $day)->toDateString();
 $dayHolidays = $holidays->filter(fn($h) => $h->includesDate($currentDate));
 $isToday = $currentDate === $today;
 @endphp

 <div class="min-h-[120px] p-2 border border-gray-200 {{ $isToday ? 'bg-blue-50 ' : 'bg-white ' }} hover:bg-gray-50 :bg-gray-700">
 <div class="flex items-center justify-between mb-2">
 <span class="text-sm font-semibold {{ $isToday ? 'text-blue-600 ' : 'text-gray-700 ' }}">
 {{ $day }}
 </span>
 @if($isToday)
 <span class="text-xs px-2 py-0.5 bg-blue-600 text-white rounded-full">اليوم</span>
 @endif
 </div>

 {{-- Display holidays for this day --}}
 <div class="space-y-1">
 @foreach($dayHolidays as $holiday)
 <div class="text-xs p-1.5 rounded {{
 $holiday->type === 'holiday' ? 'bg-red-100 text-red-700 ' :
 ($holiday->type === 'vacation' ? 'bg-blue-100 text-blue-700 ' :
 ($holiday->type === 'special_event' ? 'bg-yellow-100 text-yellow-700 ' : 'bg-gray-100 text-gray-700 '))
 }}">
 <p class="font-medium truncate">{{ $holiday->name }}</p>
 @if($holiday->is_recurring)
 <span class="text-[10px]">🔄 متكرر</span>
 @endif
 </div>
 @endforeach
 </div>
 </div>
 @endfor

 {{-- Empty cells after last day --}}
 @php
 $lastDayOfWeek = \Carbon\Carbon::create($year, $month, $daysInMonth)->dayOfWeek;
 $emptyCellsAfter = 6 - $lastDayOfWeek;
 @endphp
 @for($i = 0; $i < $emptyCellsAfter; $i++)
 <div class="min-h-[120px] p-2 bg-gray-50 border border-gray-200 "></div>
 @endfor
 </div>
 </div>

 {{-- Legend --}}
 <div class="bg-white border border-gray-200 rounded-lg p-4 mt-6">
 <div class="flex items-center gap-6 text-sm">
 <span class="text-gray-700 font-medium">الدلالات:</span>
 <div class="flex items-center gap-2">
 <div class="w-4 h-4 rounded bg-red-100 "></div>
 <span class="text-gray-600 ">عطلة رسمية</span>
 </div>
 <div class="flex items-center gap-2">
 <div class="w-4 h-4 rounded bg-blue-100 "></div>
 <span class="text-gray-600 ">إجازة</span>
 </div>
 <div class="flex items-center gap-2">
 <div class="w-4 h-4 rounded bg-yellow-100 "></div>
 <span class="text-gray-600 ">مناسبة خاصة</span>
 </div>
 </div>
 </div>
</div>
@endsection
