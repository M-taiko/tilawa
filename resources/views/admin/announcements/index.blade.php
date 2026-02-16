@extends('layouts.app')

@section('title', 'الإعلانات')

@section('content')
{{-- Page Header --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
 <div>
 <h1 class="text-3xl font-bold text-gray-900 ">الإعلانات</h1>
 <p class="text-sm text-gray-500 mt-1">إدارة الإعلانات والتنبيهات</p>
 </div>
 <x-button href="{{ route('admin.announcements.create') }}" variant="primary">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
 </svg>
 إضافة إعلان
 </x-button>
</div>

{{-- Announcements List --}}
<div class="space-y-4">
 @forelse($announcements as $announcement)
 <div class="bg-white border border-gray-200 rounded-lg p-6
 {{ !$announcement->is_active ? 'opacity-60' : '' }}">
 <div class="flex items-start justify-between gap-4">
 <div class="flex-1">
 <div class="flex items-center gap-3 mb-2">
 <h3 class="text-xl font-semibold text-gray-900 ">{{ $announcement->title }}</h3>

 {{-- Priority Badge --}}
 @if($announcement->priority === 'urgent')
 <x-badge variant="danger">عاجل</x-badge>
 @elseif($announcement->priority === 'high')
 <x-badge variant="warning">مهم</x-badge>
 @endif

 {{-- Status Badge --}}
 @if($announcement->is_active)
 <x-badge variant="success">نشط</x-badge>
 @else
 <x-badge variant="secondary">معطل</x-badge>
 @endif

 {{-- Target Audience Badge --}}
 @if($announcement->target_audience === 'teachers')
 <x-badge variant="primary">معلمين</x-badge>
 @elseif($announcement->target_audience === 'students')
 <x-badge variant="info">طلاب</x-badge>
 @else
 <x-badge variant="secondary">الكل</x-badge>
 @endif
 </div>

 <p class="text-gray-600 mb-3">{{ $announcement->content }}</p>

 <div class="flex items-center gap-4 text-sm text-gray-500 ">
 <span class="flex items-center gap-1">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
 </svg>
 {{ $announcement->creator?->name ?? 'غير محدد' }}
 </span>
 <span class="flex items-center gap-1">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
 </svg>
 {{ $announcement->created_at->format('Y-m-d H:i') }}
 </span>
 @if($announcement->expires_at)
 <span class="flex items-center gap-1">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 ينتهي: {{ $announcement->expires_at->format('Y-m-d') }}
 </span>
 @endif
 </div>
 </div>

 <div class="flex items-center gap-2">
 <x-button href="{{ route('admin.announcements.edit', $announcement) }}" variant="outline" size="sm">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
 </svg>
 تعديل
 </x-button>

 <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" class="inline">
 @csrf
 @method('DELETE')
 <x-button type="submit" variant="danger" size="sm" onclick="return confirm('هل أنت متأكد من حذف هذا الإعلان؟')">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
 </svg>
 حذف
 </x-button>
 </form>
 </div>
 </div>
 </div>
 @empty
 <div class="bg-white border border-gray-200 rounded-lg p-12 text-center">
 <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
 </svg>
 <h3 class="text-lg font-semibold text-gray-900 mb-2">لا توجد إعلانات</h3>
 <p class="text-gray-600 mb-4">ابدأ بإضافة إعلان جديد</p>
 <x-button href="{{ route('admin.announcements.create') }}" variant="primary">
 إضافة إعلان
 </x-button>
 </div>
 @endforelse
</div>

{{-- Pagination --}}
@if($announcements->hasPages())
 <div class="mt-6">
 {{ $announcements->links() }}
 </div>
@endif
@endsection
