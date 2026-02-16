@extends('layouts.app')

@section('title', 'سجل النشاطات')

@section('content')
{{-- Page Header --}}
<div class="mb-8">
 <h1 class="text-3xl font-bold text-gray-900 ">سجل النشاطات</h1>
 <p class="text-sm text-gray-500 mt-1">تتبع جميع العمليات والتغييرات في النظام</p>
</div>

{{-- Filters --}}
<div class="bg-white border border-gray-200 rounded-lg p-4 mb-6">
 <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="flex flex-wrap gap-4 items-end">
 <div class="flex-1 min-w-[200px]">
 <label class="block text-sm font-medium text-gray-700 mb-2">النوع</label>
 <select name="action" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 ">
 <option value="">جميع الأنواع</option>
 <option value="created" {{ request('action') === 'created' ? 'selected' : '' }}>إضافة</option>
 <option value="updated" {{ request('action') === 'updated' ? 'selected' : '' }}>تعديل</option>
 <option value="deleted" {{ request('action') === 'deleted' ? 'selected' : '' }}>حذف</option>
 </select>
 </div>

 <div class="flex-1 min-w-[200px]">
 <label class="block text-sm font-medium text-gray-700 mb-2">المستخدم</label>
 <input type="text" name="user" value="{{ request('user') }}" placeholder="اسم المستخدم..."
 class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 ">
 </div>

 <div class="flex-1 min-w-[200px]">
 <label class="block text-sm font-medium text-gray-700 mb-2">من تاريخ</label>
 <input type="date" name="from" value="{{ request('from') }}"
 class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 ">
 </div>

 <div class="flex gap-2">
 <x-button type="submit" variant="primary">بحث</x-button>
 @if(request()->hasAny(['action', 'user', 'from']))
 <x-button href="{{ route('admin.activity-logs.index') }}" variant="outline">إعادة تعيين</x-button>
 @endif
 </div>
 </form>
</div>

{{-- Activity Log Timeline --}}
<div class="space-y-4">
 @forelse($logs as $log)
 <div class="bg-white border border-gray-200 rounded-lg p-6">
 <div class="flex items-start gap-4">
 {{-- Icon based on action --}}
 <div class="flex-shrink-0">
 @if($log->action === 'created')
 <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
 <svg class="w-5 h-5 text-green-600 " fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
 </svg>
 </div>
 @elseif($log->action === 'updated')
 <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
 <svg class="w-5 h-5 text-blue-600 " fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
 </svg>
 </div>
 @elseif($log->action === 'deleted')
 <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
 <svg class="w-5 h-5 text-red-600 " fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
 </svg>
 </div>
 @else
 <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
 <svg class="w-5 h-5 text-gray-600 " fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </div>
 @endif
 </div>

 {{-- Content --}}
 <div class="flex-1 min-w-0">
 <div class="flex items-start justify-between gap-4 mb-2">
 <div>
 <p class="text-gray-900 font-medium">
 <span class="font-semibold">{{ $log->user?->name ?? 'نظام' }}</span>
 @if($log->action === 'created')
 <span class="text-green-600 ">أضاف</span>
 @elseif($log->action === 'updated')
 <span class="text-blue-600 ">عدّل</span>
 @elseif($log->action === 'deleted')
 <span class="text-red-600 ">حذف</span>
 @else
 <span>{{ $log->action }}</span>
 @endif
 <span class="text-gray-600 ">{{ class_basename($log->model_type) }}</span>
 @if($log->model_id)
 <span class="text-gray-500 ">#{{ $log->model_id }}</span>
 @endif
 </p>

 <div class="flex items-center gap-3 mt-1 text-sm text-gray-500 ">
 <span class="flex items-center gap-1">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 {{ $log->created_at->diffForHumans() }}
 </span>
 <span class="flex items-center gap-1">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
 </svg>
 {{ $log->ip_address }}
 </span>
 </div>
 </div>

 <button onclick="toggleDetails('log-{{ $log->id }}')"
 class="text-sm text-blue-600 hover:text-blue-700 :text-blue-300">
 عرض التفاصيل
 </button>
 </div>

 {{-- Details (hidden by default) --}}
 <div id="log-{{ $log->id }}" class="hidden mt-3 p-3 bg-gray-50 rounded-lg text-sm">
 @if($log->old_values && count($log->old_values) > 0)
 <div class="mb-3">
 <h4 class="font-semibold text-gray-700 mb-2">القيم القديمة:</h4>
 <pre class="text-xs text-gray-600 overflow-x-auto">{{ json_encode($log->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
 </div>
 @endif

 @if($log->new_values && count($log->new_values) > 0)
 <div>
 <h4 class="font-semibold text-gray-700 mb-2">القيم الجديدة:</h4>
 <pre class="text-xs text-gray-600 overflow-x-auto">{{ json_encode($log->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
 </div>
 @endif

 <div class="mt-3 text-xs text-gray-500 ">
 <p>User Agent: {{ $log->user_agent }}</p>
 </div>
 </div>
 </div>
 </div>
 </div>
 @empty
 <div class="bg-white border border-gray-200 rounded-lg p-12 text-center">
 <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
 </svg>
 <h3 class="text-lg font-semibold text-gray-900 mb-2">لا توجد نشاطات</h3>
 <p class="text-gray-600 ">لم يتم تسجيل أي نشاطات بعد</p>
 </div>
 @endforelse
</div>

{{-- Pagination --}}
@if($logs->hasPages())
 <div class="mt-6">
 {{ $logs->links() }}
 </div>
@endif

<script>
function toggleDetails(id) {
 const element = document.getElementById(id);
 element.classList.toggle('hidden');
}
</script>
@endsection
