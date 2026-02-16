@extends('layouts.app')

@section('title', 'مهارات التأسيس')

@section('content')
{{-- Page Header --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
 <div>
 <h1 class="text-3xl font-bold text-gray-900">مهارات التأسيس</h1>
 <p class="text-sm text-gray-500 mt-1">إدارة قائمة المهارات الأساسية</p>
 </div>
 <x-button href="{{ route('admin.foundation-skills.create') }}" variant="primary">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
 </svg>
 إضافة مهارة
 </x-button>
</div>

{{-- Skills Table --}}
<x-table>
 <x-table.head>
 <x-table.heading>الاسم</x-table.heading>
 <x-table.heading>الترتيب</x-table.heading>
 <x-table.heading>الحالة</x-table.heading>
 <x-table.heading>الإجراءات</x-table.heading>
 </x-table.head>
 <x-table.body>
 @forelse ($skills as $skill)
 <x-table.row>
 <x-table.cell class="font-medium text-gray-900">
 {{ $skill->name }}
 </x-table.cell>
 <x-table.cell>
 {{ $skill->sort_order }}
 </x-table.cell>
 <x-table.cell>
 @if($skill->is_active)
 <x-badge variant="success">نشطة</x-badge>
 @else
 <x-badge variant="secondary">غير نشطة</x-badge>
 @endif
 </x-table.cell>
 <x-table.cell>
 <div class="flex items-center gap-2 justify-end">
 <x-button href="{{ route('admin.foundation-skills.edit', $skill) }}" variant="outline" size="sm">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
 </svg>
 تعديل
 </x-button>
 <form method="POST" action="{{ route('admin.foundation-skills.destroy', $skill) }}" class="inline">
 @csrf
 @method('DELETE')
 <x-button type="submit" variant="danger" size="sm" onclick="return confirm('هل أنت متأكد من حذف هذه المهارة؟')">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
 </svg>
 حذف
 </x-button>
 </form>
 </div>
 </x-table.cell>
 </x-table.row>
 @empty
 <x-table.empty title="لا توجد مهارات" description="ابدأ بإضافة مهارة جديدة" cols="4">
 <x-slot:icon>
 <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </x-slot:icon>
 </x-table.empty>
 @endforelse
 </x-table.body>
</x-table>

{{-- Pagination --}}
<div class="mt-6">
 {{ $skills->links() }}
</div>
@endsection
