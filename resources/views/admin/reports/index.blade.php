@extends('layouts.app')

@section('title', 'التقارير')

@section('content')
{{-- Page Header --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
 <div>
 <h1 class="text-3xl font-bold text-gray-900">التقارير</h1>
 <p class="text-sm text-gray-600 mt-1">تقارير شاملة عن أداء الطلاب والمعلمين</p>
 </div>
</div>

{{-- Filters Card --}}
<x-card class="mb-6">
 <x-card-header>
 <div class="flex items-center gap-3">
 <div class="p-2 rounded-lg bg-primary-100 text-primary-600">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
 </svg>
 </div>
 <h3 class="font-semibold text-gray-900">فلترة التقارير</h3>
 </div>
 </x-card-header>
 <x-card-body>
 <form method="GET" action="{{ route('admin.reports.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6">
 <div>
 <label class="block text-sm font-semibold text-gray-700 mb-2">من تاريخ</label>
 <input type="date" name="start_date" class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg text-gray-900 text-sm transition-colors focus:outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-500/20" value="{{ $startDate }}">
 </div>
 <div>
 <label class="block text-sm font-semibold text-gray-700 mb-2">إلى تاريخ</label>
 <input type="date" name="end_date" class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg text-gray-900 text-sm transition-colors focus:outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-500/20" value="{{ $endDate }}">
 </div>
 <div>
 <label class="block text-sm font-semibold text-gray-700 mb-2">الطلاب غير النشطين (بالأيام)</label>
 <input type="number" name="inactive_days" class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg text-gray-900 text-sm transition-colors focus:outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-500/20" value="{{ $inactiveDays }}" min="1">
 </div>
 <div class="flex items-end">
 <x-button type="submit" variant="primary" class="w-full">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
 </svg>
 تحديث
 </x-button>
 </div>
 </form>
 </x-card-body>
</x-card>

{{-- Reports Grid --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
 {{-- Top Students --}}
 <div class="flex flex-col gap-4">
 <div class="flex items-center gap-3">
 <div class="p-2 rounded-lg bg-success-100 text-success-600">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
 </svg>
 </div>
 <h3 class="font-semibold text-gray-900">أفضل الطلاب (آيات جديدة)</h3>
 </div>
 <x-table>
 <x-table.head>
 <x-table.heading>الطالب</x-table.heading>
 <x-table.heading>عدد الآيات</x-table.heading>
 </x-table.head>
 <x-table.body>
 @forelse ($topStudents as $student)
 <x-table.row>
 <x-table.cell class="font-medium text-gray-900">{{ $student->name }}</x-table.cell>
 <x-table.cell>
 <x-badge variant="success">{{ $student->total_memorized }}</x-badge>
 </x-table.cell>
 </x-table.row>
 @empty
 <x-table.empty title="لا توجد بيانات" description="" cols="2">
 <x-slot:icon>
 <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </x-slot:icon>
 </x-table.empty>
 @endforelse
 </x-table.body>
 </x-table>
 </div>

 {{-- Inactive Students --}}
 <div class="flex flex-col gap-4">
 <div class="flex items-center justify-between">
 <div class="flex items-center gap-3">
 <div class="p-2 rounded-lg bg-warning-100 text-warning-600">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </div>
 <h3 class="font-semibold text-gray-900">الطلاب غير النشطين</h3>
 </div>
 <x-button href="{{ route('admin.reports.inactive-students', ['inactive_days' => $inactiveDays]) }}" variant="outline" size="sm">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
 </svg>
 عرض التقرير المفصل
 </x-button>
 </div>
 <x-table>
 <x-table.head>
 <x-table.heading>الطالب</x-table.heading>
 <x-table.heading>آخر جلسة</x-table.heading>
 </x-table.head>
 <x-table.body>
 @forelse ($inactiveStudents as $student)
 <x-table.row>
 <x-table.cell class="font-medium text-gray-900">{{ $student->name }}</x-table.cell>
 <x-table.cell>{{ $student->last_session_date ?? 'لا يوجد' }}</x-table.cell>
 </x-table.row>
 @empty
 <x-table.empty title="لا توجد بيانات" description="" cols="2">
 <x-slot:icon>
 <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </x-slot:icon>
 </x-table.empty>
 @endforelse
 </x-table.body>
 </x-table>
 </div>
</div>

{{-- Teacher Report Form --}}
<x-card>
 <x-card-header>
 <div class="flex items-center gap-3">
 <div class="p-2 rounded-lg bg-info-100 text-info-600">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
 </svg>
 </div>
 <h3 class="font-semibold text-gray-900">تقرير المعلم</h3>
 </div>
 </x-card-header>
 <x-card-body>
 <form method="GET" action="{{ route('admin.reports.teacher', 0) }}" id="teacher-report-form" data-base="{{ url('admin/reports/teacher') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6">
 <div class="md:col-span-2">
 <x-select name="teacher_id" label="المعلم" required>
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
 </svg>
 </x-slot>
 <option value="">اختر معلماً</option>
 @foreach ($teachers as $teacher)
 <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
 @endforeach
 </x-select>
 </div>
 <div>
 <label class="block text-sm font-semibold text-gray-700 mb-2">من تاريخ</label>
 <input type="date" name="start_date" class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg text-gray-900 text-sm transition-colors focus:outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-500/20" value="{{ $startDate }}">
 </div>
 <div>
 <label class="block text-sm font-semibold text-gray-700 mb-2">إلى تاريخ</label>
 <input type="date" name="end_date" class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg text-gray-900 text-sm transition-colors focus:outline-none focus:border-primary-500 focus:ring-4 focus:ring-primary-500/20" value="{{ $endDate }}">
 </div>
 <div class="md:col-span-4">
 <x-button type="submit" variant="outline" class="w-full justify-center">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
 </svg>
 عرض التقرير
 </x-button>
 </div>
 </form>
 </x-card-body>
</x-card>

<script>
 document.getElementById('teacher-report-form').addEventListener('submit', function (event) {
 const teacherId = this.querySelector('select[name="teacher_id"]').value;
 if (!teacherId) {
 event.preventDefault();
 return;
 }
 this.action = this.dataset.base + '/' + teacherId;
 });
</script>
@endsection
