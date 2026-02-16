@props(['limit' => 3])

@php
 $tenantRole = session('tenantRole');
 $audience = 'all';

 if (!auth()->user()->isSaasAdmin() && $tenantRole) {
 $audience = $tenantRole === 'teacher' ? 'teachers' : ($tenantRole === 'student' ? 'students' : $tenantRole);
 }

 $announcements = \App\Models\Announcement::where('tenant_id', session('current_tenant_id'))
 ->active()
 ->forAudience($audience)
 ->latest()
 ->limit($limit)
 ->get();

 $canViewAll = auth()->user()->isSaasAdmin() || $tenantRole === 'tenant_admin';
@endphp

@if($announcements->count() > 0)
<div class="bg-white border border-gray-200 rounded-lg p-6">
 <div class="flex items-center justify-between mb-4">
 <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
 <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
 </svg>
 الإعلانات
 </h3>
 @if($canViewAll)
 <a href="{{ route('admin.announcements.index') }}" class="text-sm text-blue-600 hover:text-blue-700">
 عرض الكل
 </a>
 @endif
 </div>

 <div class="space-y-3">
 @foreach($announcements as $announcement)
 <div class="p-4 rounded-lg border border-gray-200 
 {{ $announcement->priority === 'urgent' ? 'bg-red-50 /20 border-red-200 ' :
 ($announcement->priority === 'high' ? 'bg-yellow-50 /20 border-yellow-200 ' :
 'bg-gray-50 ') }}">
 <div class="flex items-start gap-3">
 @if($announcement->priority === 'urgent')
 <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
 </svg>
 @elseif($announcement->priority === 'high')
 <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 @else
 <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 @endif

 <div class="flex-1 min-w-0">
 <h4 class="font-semibold text-gray-900 mb-1">{{ $announcement->title }}</h4>
 <p class="text-sm text-gray-600 line-clamp-2">{{ $announcement->content }}</p>
 <div class="flex items-center gap-2 mt-2 text-xs text-gray-500 ">
 <span>{{ $announcement->created_at->diffForHumans() }}</span>
 @if($announcement->target_audience !== 'all')
 <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded">
 {{ $announcement->target_audience === 'teachers' ? 'معلمين' : 'طلاب' }}
 </span>
 @endif
 </div>
 </div>
 </div>
 </div>
 @endforeach
 </div>
</div>
@endif
