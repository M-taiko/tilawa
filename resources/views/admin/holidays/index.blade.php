@extends('layouts.app')

@section('title', 'الإجازات والعطلات')

@section('content')
{{-- Page Header --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
 <div>
 <h1 class="text-3xl font-bold text-gray-900 ">الإجازات والعطلات</h1>
 <p class="text-sm text-gray-500 mt-1">إدارة الإجازات والمناسبات الخاصة</p>
 </div>
 <div class="flex gap-2">
 <x-button href="{{ route('admin.holidays.calendar') }}" variant="outline">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
 </svg>
 عرض التقويم
 </x-button>
 <x-button href="{{ route('admin.holidays.create') }}" variant="primary">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
 </svg>
 إضافة إجازة
 </x-button>
 </div>
</div>

{{-- Holidays List --}}
<div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
 <div class="overflow-x-auto">
 <table class="w-full">
 <thead class="bg-gray-50 border-b border-gray-200 ">
 <tr>
 <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700 ">الاسم</th>
 <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700 ">النوع</th>
 <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700 ">تاريخ البداية</th>
 <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700 ">تاريخ النهاية</th>
 <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700 ">المدة</th>
 <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700 ">متكرر</th>
 <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700 ">الإجراءات</th>
 </tr>
 </thead>
 <tbody class="divide-y divide-gray-200 ">
 @forelse($holidays as $holiday)
 <tr class="hover:bg-gray-50 :bg-gray-700">
 <td class="px-4 py-3">
 <div>
 <p class="text-sm font-medium text-gray-900 ">{{ $holiday->name }}</p>
 @if($holiday->description)
 <p class="text-xs text-gray-500 mt-1">{{ $holiday->description }}</p>
 @endif
 </div>
 </td>
 <td class="px-4 py-3 text-sm">
 @if($holiday->type === 'holiday')
 <x-badge variant="primary">عطلة رسمية</x-badge>
 @elseif($holiday->type === 'vacation')
 <x-badge variant="info">إجازة</x-badge>
 @else
 <x-badge variant="warning">مناسبة خاصة</x-badge>
 @endif
 </td>
 <td class="px-4 py-3 text-sm text-gray-600 ">
 {{ $holiday->start_date->format('Y-m-d') }}
 </td>
 <td class="px-4 py-3 text-sm text-gray-600 ">
 {{ $holiday->end_date->format('Y-m-d') }}
 </td>
 <td class="px-4 py-3 text-sm text-gray-600 ">
 {{ $holiday->start_date->diffInDays($holiday->end_date) + 1 }} يوم
 </td>
 <td class="px-4 py-3 text-sm">
 @if($holiday->is_recurring)
 <x-badge variant="success">نعم</x-badge>
 @else
 <x-badge variant="secondary">لا</x-badge>
 @endif
 </td>
 <td class="px-4 py-3 text-sm">
 <div class="flex items-center gap-2 justify-end">
 <x-button href="{{ route('admin.holidays.edit', $holiday) }}" variant="outline" size="sm">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
 </svg>
 تعديل
 </x-button>
 <form action="{{ route('admin.holidays.destroy', $holiday) }}" method="POST" class="inline">
 @csrf
 @method('DELETE')
 <x-button type="submit" variant="danger" size="sm" onclick="return confirm('هل أنت متأكد من حذف هذه الإجازة؟')">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
 </svg>
 حذف
 </x-button>
 </form>
 </div>
 </td>
 </tr>
 @empty
 <tr>
 <td colspan="7" class="px-4 py-12 text-center">
 <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
 </svg>
 <h3 class="text-lg font-semibold text-gray-900 mb-2">لا توجد إجازات</h3>
 <p class="text-gray-600 mb-4">لم يتم إضافة أي إجازات أو عطلات</p>
 <x-button href="{{ route('admin.holidays.create') }}" variant="primary">
 إضافة إجازة
 </x-button>
 </td>
 </tr>
 @endforelse
 </tbody>
 </table>
 </div>
</div>

{{-- Pagination --}}
@if($holidays->hasPages())
 <div class="mt-6">
 {{ $holidays->links() }}
 </div>
@endif
@endsection
