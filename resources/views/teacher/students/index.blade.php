@extends('layouts.app')

@section('title', 'طلابي')

@section('content')
@php
    $totalStudents = method_exists($students, 'total') ? $students->total() : $students->count();
    $pageStudents = $students->count();
    $assignedCount = collect($students->items())->filter(fn ($student) => !is_null($student->class_id))->count();
    $unassignedCount = $totalStudents - $assignedCount;
@endphp

{{-- Page Header --}}
<div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-gradient-to-br from-white via-white to-slate-50 p-6 mb-6">
 <div class="absolute -top-12 -right-12 h-40 w-40 rounded-full bg-primary-100/70 blur-2xl"></div>
 <div class="absolute -bottom-16 -left-10 h-48 w-48 rounded-full bg-blue-100/60 blur-3xl"></div>
 <div class="relative">
 <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-50 text-primary-700 text-xs font-semibold border border-primary-100">
 <span class="h-2 w-2 rounded-full bg-primary-500"></span>
 قائمة الطلاب
 </div>
 <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-3">طلابي</h1>
 <p class="text-sm text-slate-600 mt-2">متابعة الطلاب المسجلين في حلقاتك بسهولة</p>
 </div>
</div>

 {{-- Quick Stats --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
 <x-gradient-stat-card label="إجمالي الطلاب" :value="$totalStudents" gradient="blue">
 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
 </svg>
 </x-gradient-stat-card>

 <x-gradient-stat-card label="المُعيّنون لحلقة" :value="$assignedCount" gradient="green">
 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </x-gradient-stat-card>

 <x-gradient-stat-card label="غير معيّنين" :value="$unassignedCount" gradient="yellow">
 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
 </svg>
 </x-gradient-stat-card>

 <x-gradient-stat-card label="الطلاب في هذه الصفحة" :value="$pageStudents" gradient="purple">
 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
 </svg>
 </x-gradient-stat-card>
</div>

 {{-- Students Table --}}
<x-card>
 <x-card-body>
 <div class="flex items-center justify-between mb-4">
 <div>
 <h3 class="text-lg font-bold text-slate-900">قائمة الطلاب</h3>
 <p class="text-sm text-slate-500 mt-1">معلومات التواصل والحلقة لكل طالب</p>
 </div>
 </div>

 <x-table>
 <x-table.head>
 <x-table.heading>الاسم</x-table.heading>
 <x-table.heading>الحلقة</x-table.heading>
 <x-table.heading>ولي الأمر</x-table.heading>
 <x-table.heading>الهاتف</x-table.heading>
 <x-table.heading>الإجراءات</x-table.heading>
 </x-table.head>
 <x-table.body>
 @forelse ($students as $student)
 @php
     $isKids = $student->group === 'kids';
     $contactName = $isKids ? ($student->parent_name ?? '-') : '-';
     $contactPhone = $isKids ? ($student->parent_phone ?? '-') : ($student->student_phone ?? '-');
     $parentUrl = $student->parent_portal_token ? url("/p/{$student->parent_portal_token}") : null;
 @endphp
 <x-table.row>
 <x-table.cell class="font-medium text-gray-900">
 {{ $student->name }}
 </x-table.cell>
 <x-table.cell>
 @if($student->class)
 <x-badge variant="primary">{{ $student->class->name }}</x-badge>
 @else
 <x-badge variant="secondary">غير معيّن</x-badge>
 @endif
 </x-table.cell>
 <x-table.cell>
 {{ $contactName }}
 </x-table.cell>
 <x-table.cell class="direction-ltr text-right">
 {{ $contactPhone }}
 </x-table.cell>
 <x-table.cell>
 <div class="flex items-center justify-end">
 @if($parentUrl)
 <x-button href="{{ $parentUrl }}" variant="ghost" size="sm" title="فتح تقرير ولي الأمر" target="_blank" rel="noopener">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
 </svg>
 تقرير
 </x-button>
 @else
 <x-button href="#" variant="ghost" size="sm" title="لا يوجد رابط تقرير" disabled>
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
 </svg>
 تقرير
 </x-button>
 @endif
 </div>
 </x-table.cell>
 </x-table.row>
 @empty
 <x-table.empty title="لا يوجد طلاب" description="" cols="5">
 <x-slot:icon>
 <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
 </svg>
 </x-slot:icon>
 </x-table.empty>
 @endforelse
 </x-table.body>
 </x-table>
 </x-card-body>
</x-card>

<div class="mt-6">
 <x-pagination :paginator="$students" align="center" />
</div>
@endsection
