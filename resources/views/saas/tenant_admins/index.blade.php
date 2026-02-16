@extends('layouts.app')

@section('title', 'مدراء المركز')

@section('content')
{{-- Page Header --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
 <div>
 <h1 class="text-3xl font-bold text-gray-900">مدراء المركز: {{ $tenant->name }}</h1>
 <p class="text-sm text-gray-600 mt-1">إدارة المستخدمين المدراء للمركز</p>
 </div>
 <div class="flex gap-3">
 <x-button href="{{ route('saas.tenants.index') }}" variant="ghost">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
 </svg>
 رجوع
 </x-button>
 <x-button href="{{ route('saas.tenant_admins.create', $tenant) }}" variant="primary">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
 </svg>
 إضافة مدير
 </x-button>
 </div>
</div>

{{-- Admins Table --}}
<x-table>
 <x-table.head>
 <x-table.heading>الاسم</x-table.heading>
 <x-table.heading>البريد الإلكتروني</x-table.heading>
 <x-table.heading>الحالة</x-table.heading>
 <x-table.heading>تاريخ الإضافة</x-table.heading>
 <x-table.heading>الإجراءات</x-table.heading>
 </x-table.head>
 <x-table.body>
 @forelse ($admins as $adminRelation)
 @php
 $admin = $adminRelation->user;
 @endphp
 <x-table.row>
 <x-table.cell class="font-medium text-gray-900">
 {{ $admin->name }}
 </x-table.cell>
 <x-table.cell>
 {{ $admin->email }}
 </x-table.cell>
 <x-table.cell>
 @if($admin->is_active)
 <x-badge variant="success">نشط</x-badge>
 @else
 <x-badge variant="danger">غير نشط</x-badge>
 @endif
 </x-table.cell>
 <x-table.cell>
 {{ $adminRelation->created_at->format('Y-m-d') }}
 </x-table.cell>
 <x-table.cell>
 <div class="flex items-center justify-end gap-2">
 <form method="POST" action="{{ route('saas.tenant_admins.toggle-status', [$tenant, $admin]) }}">
 @csrf
 <x-button type="submit" variant="{{ $admin->is_active ? 'warning' : 'success' }}" size="sm">
 @if($admin->is_active)
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
 </svg>
 تعطيل
 @else
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 تفعيل
 @endif
 </x-button>
 </form>
 <x-button href="{{ route('saas.tenant_admins.edit', [$tenant, $admin]) }}" variant="outline" size="sm">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
 </svg>
 تعديل
 </x-button>
 <form method="POST" action="{{ route('saas.tenant_admins.destroy', [$tenant, $admin]) }}" onsubmit="return confirm('هل أنت متأكد من حذف هذا المدير؟')">
 @csrf
 @method('DELETE')
 <x-button type="submit" variant="danger" size="sm">
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
 <x-table.row>
 <x-table.cell colspan="5" class="text-center py-8 text-gray-500">
 لا يوجد مدراء
 </x-table.cell>
 </x-table.row>
 @endforelse
 </x-table.body>
</x-table>
@endsection
