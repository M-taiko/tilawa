@extends('layouts.app')

@section('title', 'المعلمون')

@section('content')
{{-- Page Header --}}
<div class="mb-8">
 <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
 <div>
 <h1 class="text-3xl font-bold text-gray-900">المعلمون</h1>
 <p class="text-sm text-gray-500 mt-1">إدارة حسابات المعلمين وصلاحيات المجموعات</p>
 </div>
 <div class="flex flex-wrap gap-2">
 <x-button href="{{ route('admin.teachers.create') }}" variant="primary">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
 </svg>
 إضافة معلم
 </x-button>
 </div>
 </div>

 {{-- Quick Stats --}}
 @php
 $totalTeachers = $teachers->total();
 $tenantId = session('current_tenant_id');
 $teacherIds = \App\Models\TenantUser::where('tenant_id', $tenantId)
 ->where('role', 'teacher')
 ->pluck('user_id');
 $activeCount = \App\Models\User::whereIn('id', $teacherIds)
 ->where('is_active', true)
 ->count();
 $inactiveCount = \App\Models\User::whereIn('id', $teacherIds)
 ->where('is_active', false)
 ->count();
 @endphp

 <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
 <x-gradient-stat-card label="إجمالي المعلمين" :value="$totalTeachers" gradient="blue">
 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
 </svg>
 </x-gradient-stat-card>

 <x-gradient-stat-card label="المعلمون النشطون" :value="$activeCount" gradient="green">
 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </x-gradient-stat-card>

 <x-gradient-stat-card label="غير نشطين" :value="$inactiveCount" gradient="gray">
 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
 </svg>
 </x-gradient-stat-card>
 </div>
</div>

{{-- Bulk Actions Bar (hidden by default) --}}
<div id="bulkActionsBar" class="hidden bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-4 mb-6 shadow-sm">
 <div class="flex items-center justify-between flex-wrap gap-4">
 <div class="flex items-center gap-3">
 <div class="p-2 bg-blue-600 rounded-lg">
 <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
 </svg>
 </div>
 <div>
 <p class="text-sm font-medium text-blue-900"><span id="selectedCount">0</span> معلم محدد</p>
 <p class="text-xs text-blue-600">اختر إجراءً جماعياً</p>
 </div>
 </div>
 <div class="flex gap-2 flex-wrap">
 <x-button type="button" variant="primary" size="sm" onclick="bulkUpdateStatus('active')" title="تفعيل المعلمين المحددين" class="bg-green-600 hover:bg-green-700 border-green-600 hover:border-green-700">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 تفعيل
 </x-button>
 <x-button type="button" variant="primary" size="sm" onclick="bulkUpdateStatus('inactive')" title="تعطيل المعلمين المحددين" class="bg-yellow-600 hover:bg-yellow-700 border-yellow-600 hover:border-yellow-700">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 تعطيل
 </x-button>
 <x-button type="button" variant="danger" size="sm" onclick="bulkDelete()" title="حذف المعلمين المحددين">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
 </svg>
 حذف
 </x-button>
 <x-button type="button" variant="outline" size="sm" onclick="clearSelection()" title="إلغاء التحديد">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
 </svg>
 إلغاء
 </x-button>
 </div>
 </div>
</div>

{{-- Filters --}}
<div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 mb-6">
 <div class="flex items-center gap-2 mb-4">
 <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
 </svg>
 <h3 class="text-lg font-bold text-gray-900">تصفية وبحث</h3>
 @if(request('status') || request('search'))
 <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
 فلاتر نشطة
 </span>
 @endif
 </div>

 <form method="GET" action="{{ route('admin.teachers.index') }}">
 <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
 <div>
 <label class="block text-sm font-medium text-gray-700 mb-2">
 <span class="flex items-center gap-2">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 الحالة
 </span>
 </label>
 <select name="status" class="w-full pr-4 pl-12 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22M6%208l4%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.5rem] bg-[center_left_0.5rem] bg-no-repeat">
 <option value="">كل الحالات</option>
 <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>نشط</option>
 <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>غير نشط</option>
 </select>
 </div>

 <div>
 <label class="block text-sm font-medium text-gray-700 mb-2">
 <span class="flex items-center gap-2">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
 </svg>
 بحث
 </span>
 </label>
 <input
 type="text"
 name="search"
 value="{{ request('search') }}"
 placeholder="اسم المعلم أو البريد الإلكتروني..."
 class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
 />
 </div>
 </div>

 <div class="flex items-center gap-2">
 <x-button type="submit" variant="primary">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
 </svg>
 تطبيق الفلاتر
 </x-button>
 @if(request('status') || request('search'))
 <x-button href="{{ route('admin.teachers.index') }}" variant="outline">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
 </svg>
 إعادة تعيين
 </x-button>
 @endif
 </div>
 </form>
</div>

{{-- Teachers Table --}}
<x-table>
 <x-table.head>
 <x-table.heading class="w-12">
 <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)"
 class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
 </x-table.heading>
 <x-table.heading>الاسم</x-table.heading>
 <x-table.heading class="hidden md:table-cell">الحالة</x-table.heading>
 <x-table.heading class="hidden lg:table-cell">البريد الإلكتروني</x-table.heading>
 <x-table.heading class="hidden xl:table-cell">المجموعات المسموحة</x-table.heading>
 <x-table.heading>الإجراءات</x-table.heading>
 </x-table.head>
 <x-table.body>
 @forelse ($teachers as $teacher)
 @php
 $tenantId = session('current_tenant_id');
 $tenantPivot = $teacher->tenants->firstWhere('id', $tenantId)?->pivot;
 $groupsRaw = $tenantPivot?->allowed_groups_json;
 if (is_string($groupsRaw)) {
 $decoded = json_decode($groupsRaw, true);
 $groups = is_array($decoded) ? $decoded : [];
 } elseif (is_array($groupsRaw)) {
 $groups = $groupsRaw;
 } else {
 $groups = [];
 }
 @endphp
 <x-table.row>
 <x-table.cell>
 <input type="checkbox" class="teacher-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500"
 value="{{ $teacher->id }}"
 onchange="updateBulkActions()">
 </x-table.cell>
 <x-table.cell class="font-medium text-gray-900">
 {{ $teacher->name }}
 </x-table.cell>
 <x-table.cell class="hidden md:table-cell">
 @if($teacher->is_active)
 <x-badge variant="success">نشط</x-badge>
 @else
 <x-badge variant="danger">غير نشط</x-badge>
 @endif
 </x-table.cell>
 <x-table.cell class="hidden lg:table-cell">
 {{ $teacher->email }}
 </x-table.cell>
 <x-table.cell class="hidden xl:table-cell">
 @if(count($groups) > 0)
 <div class="flex flex-wrap gap-1.5">
 @foreach($groups as $group)
 <x-badge variant="info">{{ $group }}</x-badge>
 @endforeach
 </div>
 @else
 <x-badge variant="secondary">لا يوجد</x-badge>
 @endif
 </x-table.cell>
 <x-table.cell>
 <div class="flex items-center justify-end gap-2">
 {{-- Desktop: Icon-only buttons --}}
 <div class="hidden lg:flex gap-2">
 <x-button href="{{ route('admin.teachers.edit', $teacher) }}" variant="ghost" size="sm" title="تعديل بيانات المعلم">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
 </svg>
 </x-button>
 <form method="POST" action="{{ route('admin.teachers.toggle-status', $teacher) }}" class="inline-block">
 @csrf
 <x-button type="submit" variant="ghost" size="sm" title="{{ $teacher->is_active ? 'تعطيل المعلم' : 'تفعيل المعلم' }}" class="{{ $teacher->is_active ? 'text-yellow-600 hover:text-yellow-700 hover:bg-yellow-50' : 'text-green-600 hover:text-green-700 hover:bg-green-50' }}">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 @if($teacher->is_active)
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
 @else
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 @endif
 </svg>
 </x-button>
 </form>
 <x-button type="button" variant="ghost" size="sm" onclick="deleteTeacher({{ $teacher->id }}, '{{ route('admin.teachers.destroy', $teacher) }}')" title="حذف المعلم" class="text-red-600 hover:text-red-700 hover:bg-red-50">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
 </svg>
 </x-button>
 </div>

 {{-- Mobile/Tablet: Dropdown --}}
 <div class="lg:hidden">
 <x-button type="button" variant="ghost" size="sm" onclick="toggleDropdown(this, event)" title="المزيد من الإجراءات">
 <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
 <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
 </svg>
 </x-button>
 <div class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-200 z-10 overflow-hidden">
 <a href="{{ route('admin.teachers.edit', $teacher) }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 text-sm transition-colors">
 <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
 </svg>
 <span>تعديل</span>
 </a>
 <form method="POST" action="{{ route('admin.teachers.toggle-status', $teacher) }}" class="border-t border-gray-100">
 @csrf
 <button type="submit" class="flex items-center gap-3 w-full text-right px-4 py-3 hover:bg-gray-50 text-sm transition-colors">
 <svg class="w-4 h-4 {{ $teacher->is_active ? 'text-yellow-600' : 'text-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 @if($teacher->is_active)
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
 @else
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 @endif
 </svg>
 <span>{{ $teacher->is_active ? 'تعطيل' : 'تفعيل' }}</span>
 </button>
 </form>
 <button onclick="deleteTeacher({{ $teacher->id }}, '{{ route('admin.teachers.destroy', $teacher) }}')" class="flex items-center gap-3 w-full text-right px-4 py-3 hover:bg-red-50 text-red-600 text-sm transition-colors border-t border-gray-100">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
 </svg>
 <span>حذف</span>
 </button>
 </div>
 </div>
 </div>
 </x-table.cell>
 </x-table.row>
 @empty
 <tr>
 <td colspan="6" class="px-4 py-16">
 <div class="text-center">
 <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
 <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
 </svg>
 </div>
 <h3 class="text-lg font-semibold text-gray-900 mb-2">لا يوجد معلمون</h3>
 <p class="text-gray-500 mb-6 max-w-sm mx-auto">
 @if(request('status') || request('search'))
 لم يتم العثور على نتائج مطابقة للفلاتر المطبقة. جرب تغيير معايير البحث.
 @else
 ابدأ بإضافة معلمين جدد لإدارة الحلقات والطلاب
 @endif
 </p>
 <div class="flex items-center justify-center gap-3">
 @if(request('status') || request('search'))
 <x-button href="{{ route('admin.teachers.index') }}" variant="outline">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
 </svg>
 مسح الفلاتر
 </x-button>
 @endif
 <x-button href="{{ route('admin.teachers.create') }}" variant="primary">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
 </svg>
 إضافة معلم جديد
 </x-button>
 </div>
 </div>
 </td>
 </tr>
 @endforelse
 </x-table.body>
</x-table>

{{-- Pagination --}}
<x-pagination :paginator="$teachers" align="start" />

<script>
function toggleDropdown(button, event) {
 event.stopPropagation();
 const dropdown = button.nextElementSibling;

 // Close all other dropdowns
 document.querySelectorAll('[class*="absolute"][class*="bg-white"]').forEach(d => {
 if (d !== dropdown) d.classList.add('hidden');
 });

 dropdown.classList.toggle('hidden');

 // Close when clicking outside
 if (!dropdown.classList.contains('hidden')) {
 const closeHandler = function(e) {
 if (!button.contains(e.target) && !dropdown.contains(e.target)) {
 dropdown.classList.add('hidden');
 document.removeEventListener('click', closeHandler);
 }
 };
 setTimeout(() => document.addEventListener('click', closeHandler), 10);
 }
}

function deleteTeacher(teacherId, deleteUrl) {
 if (confirm('هل أنت متأكد من حذف هذا المعلم؟')) {
 const form = document.createElement('form');
 form.method = 'POST';
 form.action = deleteUrl;

 const csrfToken = document.createElement('input');
 csrfToken.type = 'hidden';
 csrfToken.name = '_token';
 csrfToken.value = '{{ csrf_token() }}';

 const methodField = document.createElement('input');
 methodField.type = 'hidden';
 methodField.name = '_method';
 methodField.value = 'DELETE';

 form.appendChild(csrfToken);
 form.appendChild(methodField);
 document.body.appendChild(form);
 form.submit();
 }
}

// Bulk Actions JavaScript
function toggleSelectAll(checkbox) {
 document.querySelectorAll('.teacher-checkbox').forEach(cb => {
 cb.checked = checkbox.checked;
 });
 updateBulkActions();
}

function updateBulkActions() {
 const checkboxes = document.querySelectorAll('.teacher-checkbox:checked');
 const bulkBar = document.getElementById('bulkActionsBar');
 const selectedCount = document.getElementById('selectedCount');

 if (checkboxes.length > 0) {
 bulkBar.classList.remove('hidden');
 selectedCount.textContent = checkboxes.length;
 } else {
 bulkBar.classList.add('hidden');
 }
}

function getSelectedIds() {
 return Array.from(document.querySelectorAll('.teacher-checkbox:checked')).map(cb => cb.value);
}

function clearSelection() {
 document.querySelectorAll('.teacher-checkbox').forEach(cb => cb.checked = false);
 document.getElementById('selectAll').checked = false;
 updateBulkActions();
}

function bulkUpdateStatus(status) {
 const ids = getSelectedIds();
 if (ids.length === 0) return;

 const statusNames = {
 'active': 'نشط',
 'inactive': 'غير نشط'
 };

 const statusName = statusNames[status] || status;

 if (confirm(`هل أنت متأكد من تغيير حالة ${ids.length} معلم إلى "${statusName}"؟`)) {
 submitBulkAction('{{ route("admin.bulk.teachers.status") }}', { teacher_ids: ids, status: status });
 }
}

function bulkDelete() {
 const ids = getSelectedIds();
 if (ids.length === 0) return;

 if (confirm(`هل أنت متأكد من حذف ${ids.length} معلم؟ لن يمكن التراجع عن هذا الإجراء.`)) {
 const form = document.createElement('form');
 form.method = 'POST';
 form.action = '{{ route("admin.bulk.teachers.delete") }}';

 const csrf = document.createElement('input');
 csrf.type = 'hidden';
 csrf.name = '_token';
 csrf.value = '{{ csrf_token() }}';
 form.appendChild(csrf);

 const method = document.createElement('input');
 method.type = 'hidden';
 method.name = '_method';
 method.value = 'DELETE';
 form.appendChild(method);

 ids.forEach(id => {
 const input = document.createElement('input');
 input.type = 'hidden';
 input.name = 'teacher_ids[]';
 input.value = id;
 form.appendChild(input);
 });

 document.body.appendChild(form);
 form.submit();
 }
}

function submitBulkAction(url, data) {
 const form = document.createElement('form');
 form.method = 'POST';
 form.action = url;

 const csrf = document.createElement('input');
 csrf.type = 'hidden';
 csrf.name = '_token';
 csrf.value = '{{ csrf_token() }}';
 form.appendChild(csrf);

 for (const [key, value] of Object.entries(data)) {
 if (Array.isArray(value)) {
 value.forEach(v => {
 const input = document.createElement('input');
 input.type = 'hidden';
 input.name = key + '[]';
 input.value = v;
 form.appendChild(input);
 });
 } else {
 const input = document.createElement('input');
 input.type = 'hidden';
 input.name = key;
 input.value = value;
 form.appendChild(input);
 }
 }

 document.body.appendChild(form);
 form.submit();
}
</script>
@endsection
