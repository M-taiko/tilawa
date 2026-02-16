@extends('layouts.app')

@section('title', 'سجل التحويلات')

@section('content')
<div class="max-w-7xl mx-auto">
 <div class="flex items-center justify-between mb-8">
 <div>
 <h1 class="text-3xl font-bold text-gray-900 ">سجل التحويلات</h1>
 <p class="text-sm text-gray-500 mt-1">الطالب: {{ $student->name }}</p>
 </div>
 <div class="flex gap-2">
 <a href="{{ route('admin.students.transfer.form', $student) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
 نقل جديد
 </a>
 <a href="{{ route('admin.students.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 :bg-gray-600">
 رجوع
 </a>
 </div>
 </div>

 {{-- Timeline --}}
 <div class="space-y-4">
 @forelse($transfers as $transfer)
 <div class="bg-white border border-gray-200 rounded-lg p-6">
 <div class="flex items-start gap-4">
 {{-- Icon --}}
 <div class="flex-shrink-0">
 <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
 <svg class="w-6 h-6 text-blue-600 " fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
 </svg>
 </div>
 </div>

 {{-- Content --}}
 <div class="flex-1 min-w-0">
 <div class="flex items-start justify-between gap-4 mb-3">
 <div>
 <p class="text-lg font-semibold text-gray-900 ">
 نقل من
 <span class="text-blue-600 ">{{ $transfer->fromClass?->name ?? 'بدون حلقة' }}</span>
 إلى
 <span class="text-green-600 ">{{ $transfer->toClass?->name ?? 'بدون حلقة' }}</span>
 </p>
 <div class="flex items-center gap-4 mt-2 text-sm text-gray-500 ">
 <span class="flex items-center gap-1">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 {{ $transfer->transferred_at->diffForHumans() }}
 </span>
 <span class="flex items-center gap-1">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
 </svg>
 بواسطة: {{ $transfer->transferredBy?->name ?? 'غير محدد' }}
 </span>
 </div>
 </div>
 <span class="text-xs text-gray-500 whitespace-nowrap">
 {{ $transfer->transferred_at->format('Y-m-d H:i') }}
 </span>
 </div>

 {{-- Transfer Details --}}
 <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3 p-3 bg-gray-50 rounded-lg">
 <div>
 <p class="text-xs font-medium text-gray-500 mb-1">المعلم السابق:</p>
 <p class="text-sm text-gray-900 ">
 {{ $transfer->fromTeacher?->name ?? 'غير محدد' }}
 </p>
 </div>
 <div>
 <p class="text-xs font-medium text-gray-500 mb-1">المعلم الجديد:</p>
 <p class="text-sm text-gray-900 ">
 {{ $transfer->toTeacher?->name ?? 'غير محدد' }}
 </p>
 </div>
 </div>

 {{-- Reason --}}
 <div class="mb-3">
 <p class="text-xs font-medium text-gray-500 mb-1">السبب:</p>
 <p class="text-sm text-gray-900 ">
 @if($transfer->reason === 'level_change')
 تغيير المستوى
 @elseif($transfer->reason === 'teacher_preference')
 طلب ولي الأمر للمعلم
 @elseif($transfer->reason === 'schedule_conflict')
 تعارض في الجدول
 @elseif($transfer->reason === 'class_size')
 إعادة توزيع الأعداد
 @elseif($transfer->reason === 'behavioral_issues')
 مشاكل سلوكية
 @else
 {{ $transfer->reason }}
 @endif
 </p>
 </div>

 {{-- Notes --}}
 @if($transfer->notes)
 <div class="p-3 bg-blue-50 rounded-lg">
 <p class="text-xs font-medium text-blue-700 mb-1">ملاحظات:</p>
 <p class="text-sm text-blue-900 ">{{ $transfer->notes }}</p>
 </div>
 @endif
 </div>
 </div>
 </div>
 @empty
 <div class="bg-white border border-gray-200 rounded-lg p-12 text-center">
 <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
 </svg>
 <h3 class="text-lg font-semibold text-gray-900 mb-2">لا توجد عمليات نقل</h3>
 <p class="text-gray-600 mb-4">لم يتم نقل هذا الطالب من قبل</p>
 <a href="{{ route('admin.students.transfer.form', $student) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
 </svg>
 نقل الطالب
 </a>
 </div>
 @endforelse
 </div>
</div>
@endsection
