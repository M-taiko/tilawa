@extends('layouts.app')

@section('title', 'الجدول الأسبوعي')

@section('content')
{{-- Page Header --}}
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">الجدول الأسبوعي</h1>
            <p class="text-sm text-gray-500 mt-1">إدارة مواعيد الحلقات الدراسية</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <x-button href="{{ route('admin.schedules.calendar') }}" variant="outline">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                عرض التقويم
            </x-button>
            <x-button href="{{ route('admin.schedules.create') }}" variant="primary">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                إضافة موعد
            </x-button>
        </div>
    </div>

    {{-- Quick Stats --}}
    @php
    $totalSchedules = $schedules->total();
    $activeCount = \App\Models\ClassSchedule::where('tenant_id', session('current_tenant_id'))->where('is_active', true)->count();
    $inactiveCount = \App\Models\ClassSchedule::where('tenant_id', session('current_tenant_id'))->where('is_active', false)->count();
    $totalHours = \App\Models\ClassSchedule::where('tenant_id', session('current_tenant_id'))
        ->where('is_active', true)
        ->sum('duration_minutes') / 60;
    @endphp

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <x-gradient-stat-card label="إجمالي المواعيد" :value="$totalSchedules" gradient="blue">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </x-gradient-stat-card>

        <x-gradient-stat-card label="المواعيد النشطة" :value="$activeCount" gradient="green">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </x-gradient-stat-card>

        <x-gradient-stat-card label="غير نشطة" :value="$inactiveCount" gradient="gray">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </x-gradient-stat-card>

        <x-gradient-stat-card label="إجمالي الساعات" :value="round($totalHours, 1)" gradient="purple">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </x-gradient-stat-card>
    </div>
 </div>
 
 {{-- Filters --}}
<div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 mb-6">
    <div class="flex items-center gap-2 mb-4">
        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
        </svg>
        <h3 class="text-lg font-bold text-gray-900">تصفية وبحث</h3>
        @if(request('status') || request('day') || request('search'))
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                فلاتر نشطة
            </span>
        @endif
    </div>

    <form method="GET" action="{{ route('admin.schedules.index') }}">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        اليوم
                    </span>
                </label>
                <select name="day" class="w-full pr-4 pl-12 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22M6%208l4%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.5rem] bg-[center_left_0.5rem] bg-no-repeat">
                    <option value="">كل الأيام</option>
                    <option value="sunday" {{ request('day') === 'sunday' ? 'selected' : '' }}>الأحد</option>
                    <option value="monday" {{ request('day') === 'monday' ? 'selected' : '' }}>الاثنين</option>
                    <option value="tuesday" {{ request('day') === 'tuesday' ? 'selected' : '' }}>الثلاثاء</option>
                    <option value="wednesday" {{ request('day') === 'wednesday' ? 'selected' : '' }}>الأربعاء</option>
                    <option value="thursday" {{ request('day') === 'thursday' ? 'selected' : '' }}>الخميس</option>
                    <option value="friday" {{ request('day') === 'friday' ? 'selected' : '' }}>الجمعة</option>
                    <option value="saturday" {{ request('day') === 'saturday' ? 'selected' : '' }}>السبت</option>
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
                    placeholder="اسم الحلقة أو المعلم..."
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
            @if(request('status') || request('day') || request('search'))
                <x-button href="{{ route('admin.schedules.index') }}" variant="outline">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    إعادة تعيين
                </x-button>
            @endif
        </div>
    </form>
</div>

 {{-- Schedules Table --}}
 <x-table>
     <x-table.head>
         <x-table.heading>الحلقة</x-table.heading>
        <x-table.heading class="hidden md:table-cell">اليوم</x-table.heading>
        <x-table.heading class="hidden lg:table-cell">الوقت</x-table.heading>
        <x-table.heading class="hidden lg:table-cell">المدة</x-table.heading>
        <x-table.heading class="hidden xl:table-cell">الموقع</x-table.heading>
        <x-table.heading class="hidden md:table-cell">الحالة</x-table.heading>
        <x-table.heading>الإجراءات</x-table.heading>
    </x-table.head>
    <x-table.body>
        @php
        $dayNames = [
            'sunday' => 'الأحد',
            'monday' => 'الاثنين',
            'tuesday' => 'الثلاثاء',
            'wednesday' => 'الأربعاء',
            'thursday' => 'الخميس',
            'friday' => 'الجمعة',
            'saturday' => 'السبت',
        ];
        @endphp
         @forelse ($schedules as $schedule)
             <x-table.row>
                 <x-table.cell class="font-medium text-gray-900">
                     {{ $schedule->studyClass->name }}
                 </x-table.cell>
                <x-table.cell class="hidden md:table-cell">
                    <x-badge variant="info">{{ $dayNames[$schedule->day_of_week] }}</x-badge>
                </x-table.cell>
                <x-table.cell class="hidden lg:table-cell">
                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} -
                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                </x-table.cell>
                <x-table.cell class="hidden lg:table-cell">
                    {{ $schedule->duration_minutes }} دقيقة
                </x-table.cell>
                <x-table.cell class="hidden xl:table-cell">
                    {{ $schedule->location ?? '-' }}
                </x-table.cell>
                <x-table.cell class="hidden md:table-cell">
                    @if($schedule->is_active)
                        <x-badge variant="success">نشط</x-badge>
                    @else
                        <x-badge variant="danger">غير نشط</x-badge>
                    @endif
                </x-table.cell>
                <x-table.cell>
                    <div class="flex items-center justify-end gap-2">
                        {{-- Desktop: Icon-only buttons --}}
                        <div class="hidden lg:flex gap-2">
                            <x-button href="{{ route('admin.schedules.edit', $schedule) }}" variant="ghost" size="sm" title="تعديل الموعد">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </x-button>
                            <x-button type="button" variant="ghost" size="sm" onclick="deleteSchedule({{ $schedule->id }}, '{{ route('admin.schedules.destroy', $schedule) }}')" title="حذف الموعد" class="text-red-600 hover:text-red-700 hover:bg-red-50">
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
                                <a href="{{ route('admin.schedules.edit', $schedule) }}" class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 text-sm transition-colors">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    <span>تعديل</span>
                                </a>
                                <button onclick="deleteSchedule({{ $schedule->id }}, '{{ route('admin.schedules.destroy', $schedule) }}')" class="flex items-center gap-3 w-full text-right px-4 py-3 hover:bg-red-50 text-red-600 text-sm transition-colors border-t border-gray-100">
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">لا توجد مواعيد</h3>
                        <p class="text-gray-500 mb-6 max-w-sm mx-auto">
                            @if(request('status') || request('day') || request('search'))
                                لم يتم العثور على نتائج مطابقة للفلاتر المطبقة. جرب تغيير معايير البحث.
                            @else
                                ابدأ بإضافة مواعيد جديدة لجدولة الحلقات الدراسية
                            @endif
                        </p>
                        <div class="flex items-center justify-center gap-3">
                            @if(request('status') || request('day') || request('search'))
                                <x-button href="{{ route('admin.schedules.index') }}" variant="outline">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    مسح الفلاتر
                                </x-button>
                            @endif
                            <x-button href="{{ route('admin.schedules.create') }}" variant="primary">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                إضافة موعد جديد
                            </x-button>
                        </div>
                    </div>
                </td>
            </tr>
        @endforelse
    </x-table.body>
</x-table>

{{-- Pagination --}}
<x-pagination :paginator="$schedules" align="start" />

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

function deleteSchedule(scheduleId, deleteUrl) {
    if (confirm('هل أنت متأكد من حذف هذا الموعد؟')) {
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
 </script>
@endsection
