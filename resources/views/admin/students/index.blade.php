@extends('layouts.app')

@section('title', 'الطلاب')

@section('content')
{{-- Page Header --}}
<div class="mb-8">
 <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
 <div>
 <h1 class="text-3xl font-bold text-gray-900">الطلاب</h1>
 <p class="text-sm text-gray-500 mt-1">إدارة بيانات الطلاب وأولياء الأمور</p>
 </div>
 <div class="flex flex-wrap gap-2">
 <x-button href="{{ route('admin.import.students') }}" variant="outline">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
 </svg>
 استيراد
 </x-button>
 <x-button href="{{ route('admin.export.students', request()->query()) }}" variant="outline">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
 </svg>
 تصدير
 </x-button>
 <x-button href="{{ route('admin.students.create') }}" variant="primary">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
 </svg>
 إضافة طالب
 </x-button>
 </div>
 </div>

 {{-- Quick Stats --}}
 @php
 $totalStudents = $students->total();
 $activeCount = \App\Models\Student::where('tenant_id', session('current_tenant_id'))->where('status', 'active')->count();
 $graduatedCount = \App\Models\Student::where('tenant_id', session('current_tenant_id'))->where('status', 'graduated')->count();
 $inactiveCount = \App\Models\Student::where('tenant_id', session('current_tenant_id'))->where('status', 'inactive')->count();
 @endphp

 <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
 <x-gradient-stat-card label="إجمالي الطلاب" :value="$totalStudents" gradient="blue">
 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
 </svg>
 </x-gradient-stat-card>

 <x-gradient-stat-card label="الطلاب النشطون" :value="$activeCount" gradient="green">
 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </x-gradient-stat-card>

 <x-gradient-stat-card label="الخريجون" :value="$graduatedCount" gradient="purple">
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
 <p class="text-sm font-medium text-blue-900"><span id="selectedCount">0</span> طالب محدد</p>
 <p class="text-xs text-blue-600">اختر إجراءً جماعياً</p>
 </div>
 </div>
 <div class="flex gap-2 flex-wrap">
 <x-button type="button" variant="primary" size="sm" onclick="bulkUpdateStatus('active')" title="تفعيل الطلاب المحددين" class="bg-green-600 hover:bg-green-700 border-green-600 hover:border-green-700">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 تفعيل
 </x-button>
 <x-button type="button" variant="primary" size="sm" onclick="bulkUpdateStatus('inactive')" title="تعطيل الطلاب المحددين" class="bg-yellow-600 hover:bg-yellow-700 border-yellow-600 hover:border-yellow-700">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 تعطيل
 </x-button>
 <x-button type="button" variant="primary" size="sm" onclick="bulkUpdateStatus('graduated')" title="تخريج الطلاب المحددين" class="bg-purple-600 hover:bg-purple-700 border-purple-600 hover:border-purple-700">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 تخريج
 </x-button>
 <x-button type="button" variant="primary" size="sm" onclick="showBulkAssignClass()" title="تعيين حلقة للطلاب المحددين">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
 </svg>
 تعيين حلقة
 </x-button>
 <x-button type="button" variant="danger" size="sm" onclick="bulkDelete()" title="حذف الطلاب المحددين">
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
 @if(request('status') || request('group') || request('track') || request('search'))
 <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
 فلاتر نشطة
 </span>
 @endif
 </div>

 <form method="GET" action="{{ route('admin.students.index') }}">
 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
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
 <option value="graduated" {{ request('status') === 'graduated' ? 'selected' : '' }}>خريج</option>
 <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>غير نشط</option>
 </select>
 </div>

 <div>
 <label class="block text-sm font-medium text-gray-700 mb-2">
 <span class="flex items-center gap-2">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
 </svg>
 المجموعة
 </span>
 </label>
 <select name="group" class="w-full pr-4 pl-12 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22M6%208l4%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.5rem] bg-[center_left_0.5rem] bg-no-repeat">
 <option value="">الكل</option>
 <option value="men" {{ request('group') === 'men' ? 'selected' : '' }}>رجال</option>
 <option value="women" {{ request('group') === 'women' ? 'selected' : '' }}>نساء</option>
 <option value="kids" {{ request('group') === 'kids' ? 'selected' : '' }}>أطفال</option>
 </select>
 </div>

 <div>
 <label class="block text-sm font-medium text-gray-700 mb-2">
 <span class="flex items-center gap-2">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
 </svg>
 المسار
 </span>
 </label>
 <select name="track" class="w-full pr-4 pl-12 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22M6%208l4%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.5rem] bg-[center_left_0.5rem] bg-no-repeat">
 <option value="">الكل</option>
 <option value="memorization" {{ request('track') === 'memorization' ? 'selected' : '' }}>حفظ</option>
 <option value="foundation" {{ request('track') === 'foundation' ? 'selected' : '' }}>تأسيس</option>
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
 placeholder="اسم الطالب، ولي الأمر، أو رقم الهاتف..."
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
 @if(request('status') || request('group') || request('track') || request('search'))
 <x-button href="{{ route('admin.students.index') }}" variant="outline">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
 </svg>
 إعادة تعيين
 </x-button>
 @endif
 </div>
 </form>
</div>

{{-- Students Table --}}
<x-table>
 <x-table.head>
 <x-table.heading class="w-12">
 <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)"
 class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 ">
 </x-table.heading>
 <x-table.heading>الاسم</x-table.heading>
 <x-table.heading class="hidden md:table-cell">الحالة</x-table.heading>
 <x-table.heading class="hidden lg:table-cell">المجموعة</x-table.heading>
 <x-table.heading class="hidden lg:table-cell">المسار</x-table.heading>
 <x-table.heading class="hidden xl:table-cell">الحلقة</x-table.heading>
 <x-table.heading class="hidden xl:table-cell">ولي الأمر</x-table.heading>
 <x-table.heading class="hidden xl:table-cell">الهاتف</x-table.heading>
 <x-table.heading>الإجراءات</x-table.heading>
 </x-table.head>
 <x-table.body>
 @forelse ($students as $student)
 @php
 $parentUrl = url("/p/{$student->parent_portal_token}");
 $parentPhone = preg_replace('/\D+/', '', $student->parent_phone ?? '');
 $waMessage = "السلام عليكم ورحمة الله وبركاته\n\n";
 $waMessage .= "نشكركم على ثقتكم بمركز تحفيظ القرآن 🌟\n\n";
 $waMessage .= "يسعدنا إرسال رابط متابعة الطالب/ة: *{$student->name}*\n\n";
 $waMessage .= "من خلال هذا الرابط يمكنكم:\n";
 $waMessage .= "✅ متابعة التقدم اليومي في الحفظ\n";
 $waMessage .= "✅ الاطلاع على التقييمات والملاحظات\n";
 $waMessage .= "✅ مراجعة سجل الحضور والغياب\n";
 $waMessage .= "✅ متابعة الإحصائيات والرسوم البيانية\n\n";
 $waMessage .= "🔗 رابط المتابعة:\n{$parentUrl}\n\n";
 $waMessage .= "_احفظ هذا الرابط للرجوع إليه في أي وقت_\n\n";
 $waMessage .= "للاستفسارات، نحن في خدمتكم دائماً 🤝";
 $waText = urlencode($waMessage);
 $waLink = $parentPhone ? "https://wa.me/{$parentPhone}?text={$waText}" : null;
 @endphp
 <x-table.row>
 <x-table.cell>
 <input type="checkbox" class="student-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500"
 value="{{ $student->id }}"
 data-group="{{ $student->group }}"
 data-track="{{ $student->track }}"
 onchange="updateBulkActions()">
 </x-table.cell>
 <x-table.cell class="font-medium text-gray-900 ">
 {{ $student->name }}
 </x-table.cell>
 <x-table.cell class="hidden md:table-cell">
 @if($student->status === 'active')
 <x-badge variant="success">نشط</x-badge>
 @elseif($student->status === 'graduated')
 <x-badge variant="info">خريج</x-badge>
 @else
 <x-badge variant="danger">غير نشط</x-badge>
 @endif
 </x-table.cell>
 <x-table.cell class="hidden lg:table-cell">
 @if($student->group === 'men')
 <x-badge variant="primary">رجال</x-badge>
 @elseif($student->group === 'women')
 <x-badge variant="secondary">نساء</x-badge>
 @elseif($student->group === 'kids')
 <x-badge variant="success">أطفال</x-badge>
 @elseif($student->group === 'foundation')
 <x-badge variant="warning">تأسيس</x-badge>
 @else
 <x-badge variant="secondary">-</x-badge>
 @endif
 </x-table.cell>
 <x-table.cell class="hidden lg:table-cell">
 @if($student->track === 'memorization')
 <x-badge variant="primary">حفظ</x-badge>
 @elseif($student->track === 'foundation')
 <x-badge variant="warning">تأسيس</x-badge>
 @else
 <x-badge variant="secondary">-</x-badge>
 @endif
 </x-table.cell>
 <x-table.cell class="hidden xl:table-cell">
 @if($student->class)
 <x-badge variant="primary">{{ $student->class->name }}</x-badge>
 @else
 <x-badge variant="secondary">غير معيّن</x-badge>
 @endif
 </x-table.cell>
 <x-table.cell class="hidden xl:table-cell">
 {{ $student->parent_name }}
 </x-table.cell>
 <x-table.cell class="hidden xl:table-cell direction-ltr text-right">
 {{ $student->parent_phone }}
 </x-table.cell>
 <x-table.cell>
 <div class="flex items-center justify-end gap-2">
 {{-- Desktop: Icon-only buttons --}}
 <div class="hidden lg:flex gap-2">
 <x-button href="{{ route('admin.students.progress', $student) }}" variant="ghost" size="sm" title="عرض صفحة التقدم الشاملة">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
 </svg>
 </x-button>
 <x-button href="{{ route('admin.students.edit', $student) }}" variant="ghost" size="sm" title="تعديل بيانات الطالب">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
 </svg>
 </x-button>
 @if($waLink)
 <x-button href="{{ $waLink }}" variant="ghost" size="sm" target="_blank" rel="noopener" title="إرسال رابط التقرير عبر واتساب لولي الأمر">
 <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 24 24">
 <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
 </svg>
 </x-button>
 @endif
 <x-button type="button" variant="ghost" size="sm" onclick="deleteStudent({{ $student->id }}, '{{ route('admin.students.destroy', $student) }}')" title="حذف الطالب" class="text-red-600 hover:text-red-700 hover:bg-red-50">
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
 <a href="{{ $parentUrl }}" target="_blank" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 text-sm transition-colors">
 <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
 </svg>
 <span>عرض التقرير</span>
 </a>
 <a href="{{ route('admin.students.edit', $student) }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 text-sm transition-colors border-t border-gray-100">
 <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
 </svg>
 <span>تعديل</span>
 </a>
 @if($waLink)
 <a href="{{ $waLink }}" target="_blank" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 text-sm transition-colors border-t border-gray-100">
 <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 24 24">
 <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
 </svg>
 <span>إرسال واتساب</span>
 </a>
 @endif
 <button onclick="deleteStudent({{ $student->id }}, '{{ route('admin.students.destroy', $student) }}')" class="flex items-center gap-3 w-full text-right px-4 py-3 hover:bg-red-50 text-red-600 text-sm transition-colors border-t border-gray-100">
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
 <td colspan="8" class="px-4 py-16">
 <div class="text-center">
 <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
 <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
 </svg>
 </div>
 <h3 class="text-lg font-semibold text-gray-900 mb-2">لا يوجد طلاب</h3>
 <p class="text-gray-500 mb-6 max-w-sm mx-auto">
 @if(request('status') || request('group') || request('track') || request('search'))
 لم يتم العثور على نتائج مطابقة للفلاتر المطبقة. جرب تغيير معايير البحث.
 @else
 ابدأ بإضافة طلاب جدد لإدارة بياناتهم ومتابعة تقدمهم
 @endif
 </p>
 <div class="flex items-center justify-center gap-3">
 @if(request('status') || request('group') || request('track') || request('search'))
 <x-button href="{{ route('admin.students.index') }}" variant="outline">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
 </svg>
 مسح الفلاتر
 </x-button>
 @endif
 <x-button href="{{ route('admin.students.create') }}" variant="primary">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
 </svg>
 إضافة طالب جديد
 </x-button>
 </div>
 </div>
 </td>
 </tr>
 @endforelse
 </x-table.body>
</x-table>

{{-- Pagination --}}
<x-pagination :paginator="$students" align="start" />

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

function deleteStudent(studentId, deleteUrl) {
 if (confirm('هل أنت متأكد من حذف هذا الطالب؟')) {
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
 document.querySelectorAll('.student-checkbox').forEach(cb => {
 cb.checked = checkbox.checked;
 });
 updateBulkActions();
}

function updateBulkActions() {
 const checkboxes = document.querySelectorAll('.student-checkbox:checked');
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
 return Array.from(document.querySelectorAll('.student-checkbox:checked')).map(cb => cb.value);
}

function clearSelection() {
 document.querySelectorAll('.student-checkbox').forEach(cb => cb.checked = false);
 document.getElementById('selectAll').checked = false;
 updateBulkActions();
}

function bulkUpdateStatus(status) {
 const ids = getSelectedIds();
 if (ids.length === 0) return;

 const statusNames = {
 'active': 'نشط',
 'inactive': 'غير نشط',
 'graduated': 'خريج'
 };

 const statusName = statusNames[status] || status;

 if (confirm(`هل أنت متأكد من تغيير حالة ${ids.length} طالب إلى "${statusName}"؟`)) {
 submitBulkAction('{{ route("admin.bulk.students.status") }}', { student_ids: ids, status: status });
 }
}

function showBulkAssignClass() {
 const ids = getSelectedIds();
 if (ids.length === 0) return;

 // Show the class assignment modal
 document.getElementById('classAssignmentModal').classList.remove('hidden');
}

function bulkDelete() {
 const ids = getSelectedIds();
 if (ids.length === 0) return;

 if (confirm(`هل أنت متأكد من حذف ${ids.length} طالب؟ لن يمكن التراجع عن هذا الإجراء.`)) {
 const form = document.createElement('form');
 form.method = 'POST';
 form.action = '{{ route("admin.bulk.students.delete") }}';

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
 input.name = 'student_ids[]';
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

// Class Assignment Modal Functions
function closeClassModal() {
 document.getElementById('classAssignmentModal').classList.add('hidden');

 // Reset the select dropdown
 const classSelect = document.getElementById('classSelect');

 // Restore original options if they were saved
 if (originalClassOptions) {
 classSelect.innerHTML = originalClassOptions;
 }

 classSelect.value = '';
 classSelect.disabled = false;

 // Reset message
 const filterMessage = document.getElementById('classFilterMessage');
 if (filterMessage) {
 filterMessage.innerHTML = '<span class="text-gray-500">سيتم تحديث الحلقة لجميع الطلاب المحددين</span>';
 }
}

function confirmClassAssignment() {
 const ids = getSelectedIds();
 const classId = document.getElementById('classSelect').value;

 if (!classId) {
 alert('الرجاء اختيار حلقة');
 return;
 }

 if (confirm(`هل أنت متأكد من تعيين ${ids.length} طالب إلى الحلقة المحددة؟`)) {
 closeClassModal();
 submitBulkAction('{{ route("admin.bulk.students.assign-class") }}', { student_ids: ids, class_id: classId });
 }
}

// Store original class options HTML
let originalClassOptions = null;

// Update modal count when opening
function showBulkAssignClass() {
 const ids = getSelectedIds();
 if (ids.length === 0) return;

 // Get selected checkboxes
 const selectedCheckboxes = document.querySelectorAll('.student-checkbox:checked');

 // Get unique groups and tracks from selected students
 const groups = new Set();
 const tracks = new Set();

 selectedCheckboxes.forEach(checkbox => {
 const group = checkbox.dataset.group;
 const track = checkbox.dataset.track;
 if (group) groups.add(group);
 if (track) tracks.add(track);
 });

 // Validate that all students have the same group and track
 const classSelect = document.getElementById('classSelect');
 const filterMessage = document.getElementById('classFilterMessage');

 // Store original options on first run
 if (!originalClassOptions) {
 originalClassOptions = classSelect.innerHTML;
 }

 if (groups.size > 1) {
 filterMessage.innerHTML = '<span class="text-red-600 font-medium">⚠️ الطلاب المحددون من مجموعات مختلفة. يرجى اختيار طلاب من مجموعة واحدة فقط.</span>';
 classSelect.disabled = true;
 classSelect.innerHTML = '<option value="">لا يمكن التعيين - مجموعات مختلفة</option>';
 } else if (tracks.size > 1) {
 filterMessage.innerHTML = '<span class="text-red-600 font-medium">⚠️ الطلاب المحددون من مسارات مختلفة. يرجى اختيار طلاب من مسار واحد فقط.</span>';
 classSelect.disabled = true;
 classSelect.innerHTML = '<option value="">لا يمكن التعيين - مسارات مختلفة</option>';
 } else {
 // Restore original options
 classSelect.innerHTML = originalClassOptions;
 classSelect.disabled = false;

 // Filter classes to show only matching ones
 const studentGroup = Array.from(groups)[0];
 const studentTrack = Array.from(tracks)[0];

 console.log('Student group:', studentGroup, 'Student track:', studentTrack);

 const allOptions = classSelect.querySelectorAll('option');
 let hasMatchingClasses = false;
 let totalClasses = 0;

 allOptions.forEach((option, index) => {
 if (index === 0) {
 // Keep the empty option
 return;
 }

 totalClasses++;
 const optionGroup = option.dataset.group;
 const optionTrack = option.dataset.track;

 console.log(`Class option ${index}:`, option.textContent, 'group:', optionGroup, 'track:', optionTrack);

 if (optionGroup === studentGroup && optionTrack === studentTrack) {
 option.style.display = '';
 option.disabled = false;
 hasMatchingClasses = true;
 } else {
 option.style.display = 'none';
 option.disabled = true;
 }
 });

 const groupLabels = {
 'men': 'رجال',
 'women': 'نساء',
 'kids': 'أطفال'
 };
 const trackLabels = {
 'memorization': 'حفظ',
 'foundation': 'تأسيس'
 };

 console.log('Has matching classes:', hasMatchingClasses, 'Total classes:', totalClasses);

 if (hasMatchingClasses) {
 filterMessage.innerHTML = `<span class="text-blue-600 font-medium">✓ عرض الحلقات المتوافقة فقط: ${groupLabels[studentGroup] || studentGroup} - ${trackLabels[studentTrack] || studentTrack}</span>`;
 } else {
 filterMessage.innerHTML = `<span class="text-orange-600 font-medium">⚠️ لا توجد حلقات متاحة للمجموعة: ${groupLabels[studentGroup] || studentGroup} - ${trackLabels[studentTrack] || studentTrack}</span>`;
 classSelect.innerHTML = '<option value="">لا توجد حلقات متوافقة</option>';
 classSelect.disabled = true;
 }
 }

 document.getElementById('modalSelectedCount').textContent = ids.length;
 document.getElementById('classAssignmentModal').classList.remove('hidden');
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
 const modal = document.getElementById('classAssignmentModal');
 if (modal) {
 modal.addEventListener('click', function(e) {
 if (e.target === modal) {
 closeClassModal();
 }
 });
 }
});
</script>
@endsection

@push('modals')
{{-- Class Assignment Modal --}}
<x-modal
    id="classAssignmentModal"
    title="تعيين حلقة"
    subtitle="اختر الحلقة المناسبة للطلاب"
    icon-color="blue"
    size="lg"
    close-function="closeClassModal()"
>
    <x-slot:icon>
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
    </x-slot:icon>

    {{-- Body Content --}}
    <div class="mb-4">
        <div class="rounded-lg bg-blue-50 border border-blue-200 p-4">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-blue-800">
                    سيتم تعيين <span id="modalSelectedCount" class="font-bold">0</span> طالب إلى الحلقة المحددة
                </p>
            </div>
        </div>
    </div>

    <div>
        <label for="classSelect" class="block text-sm font-medium text-gray-700 mb-2">
            الحلقة
        </label>
        <select id="classSelect" class="w-full pr-4 pl-12 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22M6%208l4%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.5rem] bg-[center_left_0.5rem] bg-no-repeat">
            <option value="">اختر حلقة من القائمة</option>
            @php
            $activeClasses = \App\Models\StudyClass::where('tenant_id', session('current_tenant_id'))
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
            $groupLabels = ['men' => 'رجال', 'women' => 'نساء', 'kids' => 'أطفال'];
            $trackLabels = ['memorization' => 'حفظ', 'foundation' => 'تأسيس'];
            @endphp
            @foreach($activeClasses as $class)
                @php
                $groupLabel = $groupLabels[$class->group] ?? $class->group;
                $trackLabel = $trackLabels[$class->track] ?? $class->track;
                @endphp
                <option
                    value="{{ $class->id }}"
                    data-group="{{ $class->group }}"
                    data-track="{{ $class->track }}"
                >
                    {{ $class->name }} ({{ $groupLabel }} - {{ $trackLabel }})
                </option>
            @endforeach
        </select>
        <p id="classFilterMessage" class="mt-2 text-sm text-gray-500">سيتم تحديث الحلقة لجميع الطلاب المحددين</p>
    </div>

    <x-slot:footer>
        <x-button type="button" variant="primary" onclick="confirmClassAssignment()" class="flex-1 sm:flex-initial">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            تأكيد التعيين
        </x-button>
        <x-button type="button" variant="outline" onclick="closeClassModal()" class="flex-1 sm:flex-initial">
            إلغاء
        </x-button>
    </x-slot:footer>
</x-modal>
@endpush
