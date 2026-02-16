@extends('layouts.app')

@section('title', 'المنصّة - المراكز')

@section('content')
{{-- Page Header --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
 <div>
 <h1 class="text-3xl font-bold text-gray-900">إدارة المراكز</h1>
 <p class="text-sm text-gray-600 mt-1">إنشاء وإدارة مراكز التحفيظ (SaaS)</p>
 </div>
 <x-button href="{{ route('saas.tenants.create') }}" variant="primary">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
 </svg>
 مركز جديد
 </x-button>
</div>

{{-- Tenants Table --}}
<x-table>
 <x-table.head>
 <x-table.heading>الاسم</x-table.heading>
 <x-table.heading>الحالة</x-table.heading>
 <x-table.heading>مدير المركز</x-table.heading>
 <x-table.heading>تاريخ الإنشاء</x-table.heading>
 <x-table.heading>الإجراءات</x-table.heading>
 </x-table.head>
 <x-table.body>
 @forelse ($tenants as $tenant)
 <x-table.row>
 <x-table.cell class="font-medium text-gray-900">
 {{ $tenant->name }}
 </x-table.cell>
 <x-table.cell>
 @if($tenant->is_active)
 <x-badge variant="success">نشط</x-badge>
 @else
 <x-badge variant="danger">غير نشط</x-badge>
 @endif
 </x-table.cell>
 <x-table.cell>
 @php
 // Assuming the first user attached with 'tenant_admin' role is the admin
 // This logic might need adjustment based on your exact data model,
 // but usually there's a relation or pivot data.
 // For now displaying generic if specific relation not loaded.
 $admin = $tenant->users()->wherePivot('role', 'tenant_admin')->first();
 @endphp
 {{ $admin ? $admin->name : '-' }}
 </x-table.cell>
 <x-table.cell>
 {{ $tenant->created_at?->format('Y-m-d') }}
 </x-table.cell>
 <x-table.cell>
 <div class="flex items-center gap-2 justify-end">
 <x-button href="{{ route('saas.tenant_admins.index', $tenant) }}" variant="ghost" size="sm">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
 </svg>
 المدراء
 </x-button>
 <form method="POST" action="{{ route('saas.tenants.toggle-status', $tenant) }}" class="inline">
 @csrf
 <x-button type="submit" variant="{{ $tenant->is_active ? 'warning' : 'success' }}" size="sm">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 @if($tenant->is_active)
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
 @else
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 @endif
 </svg>
 {{ $tenant->is_active ? 'تعطيل' : 'تفعيل' }}
 </x-button>
 </form>
 <x-button href="{{ route('saas.tenants.edit', $tenant) }}" variant="outline" size="sm">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
 </svg>
 تعديل
 </x-button>
 <form method="POST" action="{{ route('saas.tenants.destroy', $tenant) }}" class="inline"
 onsubmit="return confirm('⚠️ تحذير خطير!\n\nسيتم حذف جميع بيانات المركز بشكل نهائي:\n• جميع الطلاب والمعلمين\n• جميع الحلقات والجلسات\n• جميع التقارير والبيانات\n\nهذا الإجراء لا يمكن التراجع عنه!\n\nهل أنت متأكد من المتابعة؟')">
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
 <x-table.empty title="لا توجد مراكز" description="ابدأ بإضافة مركز جديد" cols="5">
 <x-slot:icon>
 <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
 </svg>
 </x-slot:icon>
 </x-table.empty>
 @endforelse
 </x-table.body>
</x-table>

<div class="mt-6">{{ $tenants->links() }}</div>
@endsection
