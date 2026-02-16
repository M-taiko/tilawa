@extends('layouts.app')

@section('title', 'تقرير الطلاب غير النشطين')

@section('content')
{{-- Page Header --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
 <div>
 <h1 class="text-3xl font-bold text-gray-900">الطلاب غير النشطين</h1>
 <p class="text-sm text-gray-600 mt-1">طلاب لم يحضروا جلسات مؤخراً</p>
 </div>
 <x-button href="{{ route('admin.reports.index') }}" variant="ghost">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
 </svg>
 رجوع للتقارير
 </x-button>
</div>

{{-- Filters --}}
<x-card class="mb-6">
 <x-card-body>
 <form method="GET" action="{{ route('admin.reports.inactive-students') }}" class="flex flex-col md:flex-row gap-4 items-end">
 <div class="flex-1">
 <label class="block text-sm font-semibold text-gray-700 mb-2">فترة عدم النشاط</label>
 <select name="inactive_days" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring focus:ring-primary-200">
 <option value="7" {{ $inactiveDays == 7 ? 'selected' : '' }}>7 أيام</option>
 <option value="14" {{ $inactiveDays == 14 ? 'selected' : '' }}>14 يوم (افتراضي)</option>
 <option value="30" {{ $inactiveDays == 30 ? 'selected' : '' }}>30 يوم</option>
 <option value="60" {{ $inactiveDays == 60 ? 'selected' : '' }}>60 يوم</option>
 <option value="90" {{ $inactiveDays == 90 ? 'selected' : '' }}>90 يوم</option>
 </select>
 </div>

 <div class="flex-1">
 <label class="block text-sm font-semibold text-gray-700 mb-2">عدد النتائج في الصفحة</label>
 <select name="per_page" class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring focus:ring-primary-200">
 <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
 <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
 <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
 <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
 </select>
 </div>

 <x-button type="submit" variant="primary">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
 </svg>
 تطبيق الفلاتر
 </x-button>
 </form>
 </x-card-body>
</x-card>

{{-- Stats Summary --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
 <x-card>
 <x-card-body>
 <div class="flex items-center justify-between">
 <div>
 <p class="text-sm text-gray-600">إجمالي الطلاب غير النشطين</p>
 <p class="text-3xl font-bold text-red-600 mt-1">{{ $inactiveStudents->total() }}</p>
 </div>
 <div class="p-3 bg-red-100 rounded-lg">
 <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
 </svg>
 </div>
 </div>
 </x-card-body>
 </x-card>

 <x-card>
 <x-card-body>
 <div class="flex items-center justify-between">
 <div>
 <p class="text-sm text-gray-600">لم يحضروا أبداً</p>
 <p class="text-3xl font-bold text-amber-600 mt-1">
 {{ $inactiveStudents->filter(fn($s) => !$s->last_session_date)->count() }}
 </p>
 </div>
 <div class="p-3 bg-amber-100 rounded-lg">
 <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </div>
 </div>
 </x-card-body>
 </x-card>

 <x-card>
 <x-card-body>
 <div class="flex items-center justify-between">
 <div>
 <p class="text-sm text-gray-600">فترة عدم النشاط</p>
 <p class="text-3xl font-bold text-gray-900 mt-1">{{ $inactiveDays }}</p>
 <p class="text-xs text-gray-500 mt-1">يوم</p>
 </div>
 <div class="p-3 bg-gray-100 rounded-lg">
 <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </div>
 </div>
 </x-card-body>
 </x-card>
</div>

{{-- Inactive Students Table --}}
<x-card>
 <x-card-header>
 <div class="flex items-center justify-between">
 <h3 class="font-bold text-gray-900">قائمة الطلاب غير النشطين</h3>
 <span class="text-sm text-gray-600">عرض {{ $inactiveStudents->count() }} من {{ $inactiveStudents->total() }}</span>
 </div>
 </x-card-header>
 <x-card-body class="p-0">
 @if($inactiveStudents->count() > 0)
 <div class="overflow-x-auto">
 <table class="w-full">
 <thead class="bg-gray-50 border-b border-gray-200">
 <tr>
 <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase">الطالب</th>
 <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase">الحلقة</th>
 <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase">المسار</th>
 <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase">آخر جلسة</th>
 <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase">مدة عدم النشاط</th>
 <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase">الإجراءات</th>
 </tr>
 </thead>
 <tbody class="divide-y divide-gray-100">
 @foreach($inactiveStudents as $student)
 <tr class="hover:bg-gray-50 transition-colors">
 <td class="px-6 py-4">
 <div>
 <p class="font-semibold text-gray-900">{{ $student->name }}</p>
 @if($student->parent_phone)
 <p class="text-xs text-gray-500">{{ $student->parent_phone }}</p>
 @endif
 </div>
 </td>
 <td class="px-6 py-4 text-gray-700">
 {{ $student->class?->name ?? '-' }}
 </td>
 <td class="px-6 py-4">
 @if($student->track === 'memorization')
 <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 font-semibold">حفظ</span>
 @else
 <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800 font-semibold">تأسيس</span>
 @endif
 </td>
 <td class="px-6 py-4">
 @if($student->last_session_date)
 <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($student->last_session_date)->format('Y-m-d') }}</span>
 @else
 <span class="text-sm text-amber-600 font-semibold">لم يحضر أبداً</span>
 @endif
 </td>
 <td class="px-6 py-4">
 @if($student->last_session_date)
 @php
 $daysSinceLastSession = \Carbon\Carbon::parse($student->last_session_date)->diffInDays(now());
 @endphp
 <span class="text-sm font-bold {{ $daysSinceLastSession >= 30 ? 'text-red-600' : 'text-orange-600' }}">
 {{ $daysSinceLastSession }} يوم
 </span>
 @else
 <span class="text-sm text-gray-500">-</span>
 @endif
 </td>
 <td class="px-6 py-4">
 <x-button href="{{ route('admin.students.edit', $student) }}" variant="outline" size="sm">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
 </svg>
 عرض
 </x-button>
 </td>
 </tr>
 @endforeach
 </tbody>
 </table>
 </div>

 {{-- Pagination --}}
 <div class="px-6 py-4 border-t border-gray-200">
 {{ $inactiveStudents->links() }}
 </div>
 @else
 <div class="p-12 text-center">
 <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-green-100 mb-4">
 <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </div>
 <h3 class="text-lg font-bold text-gray-900 mb-2">لا توجد طلاب غير نشطين!</h3>
 <p class="text-gray-600">جميع الطلاب نشطون خلال الفترة المحددة 🎉</p>
 </div>
 @endif
 </x-card-body>
</x-card>
@endsection
