@extends('layouts.app')

@section('title', 'تقرير الطالب - ' . $student->name)

@section('content')
{{-- Compact Hero Header --}}
<div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-8">
 <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
 <div class="flex flex-col sm:flex-row sm:items-center gap-4">
 <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-purple-600 to-indigo-600 rounded-xl flex items-center justify-center">
 <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
 </svg>
 </div>
 <div>
 <h1 class="text-xl font-bold text-gray-900">{{ $student->name }}</h1>
 <div class="flex flex-wrap items-center gap-2 mt-1 text-sm">
 @if($student->class)
 <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-lg font-medium">{{ $student->class->name }}</span>
 @endif
 @if($student->group === 'men')
 <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-lg font-medium">رجال</span>
 @elseif($student->group === 'women')
 <span class="px-2 py-1 bg-pink-100 text-pink-700 rounded-lg font-medium">نساء</span>
 @else
 <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded-lg font-medium">أطفال</span>
 @endif
 </div>
 </div>
 </div>
 <x-button href="{{ route('teacher.reports.index') }}" variant="outline" size="sm">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
 </svg>
 رجوع
 </x-button>
 </div>
</div>

 {{-- Date Range Filter with Presets --}}
<div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-8">
 <div class="flex flex-col lg:flex-row gap-6 items-start">
 <div class="flex-1 w-full">
 <label class="block text-sm font-semibold text-gray-700 mb-2">الفترة الزمنية</label>
 <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
 <a href="{{ request()->fullUrlWithQuery(['start_date' => now()->startOfMonth()->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium rounded-lg {{ request('start_date') === now()->startOfMonth()->format('Y-m-d') ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-gray-50 text-gray-700 border border-gray-200 hover:bg-gray-100' }} transition-all">
 هذا الشهر
 </a>
 <a href="{{ request()->fullUrlWithQuery(['start_date' => now()->subMonths(3)->startOfMonth()->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium rounded-lg {{ request('start_date') === now()->subMonths(3)->startOfMonth()->format('Y-m-d') ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-gray-50 text-gray-700 border border-gray-200 hover:bg-gray-100' }} transition-all">
 3 أشهر
 </a>
 <a href="{{ request()->fullUrlWithQuery(['start_date' => now()->subMonths(6)->startOfMonth()->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium rounded-lg {{ request('start_date') === now()->subMonths(6)->startOfMonth()->format('Y-m-d') ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-gray-50 text-gray-700 border border-gray-200 hover:bg-gray-100' }} transition-all">
 6 أشهر
 </a>
 <a href="{{ request()->fullUrlWithQuery(['start_date' => now()->startOfYear()->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium rounded-lg {{ request('start_date') === now()->startOfYear()->format('Y-m-d') ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-gray-50 text-gray-700 border border-gray-200 hover:bg-gray-100' }} transition-all">
 هذه السنة
 </a>
 </div>
 </div>

 <div class="w-full lg:w-auto border-t lg:border-t-0 lg:border-r border-gray-200 pt-6 lg:pt-0 lg:pr-6">
 <label class="block text-sm font-semibold text-gray-700 mb-2">تحديد مخصص</label>
 <form method="GET" action="{{ route('teacher.reports.student', $student) }}" class="flex flex-col sm:flex-row gap-3">
 <div class="flex-1">
 <x-input name="start_date" label="من تاريخ" type="date" :value="$startDate">
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
 </svg>
 </x-slot>
 </x-input>
 </div>
 <div class="flex-1">
 <x-input name="end_date" label="إلى تاريخ" type="date" :value="$endDate">
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
 </svg>
 </x-slot>
 </x-input>
 </div>
 <x-button type="submit" variant="primary" class="h-[42px] self-end">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
 </svg>
 بحث
 </x-button>
 </form>
 </div>
 </div>
</div>

 {{-- Key Stats with Compact Cards --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
 <div class="bg-white rounded-lg border border-gray-200 p-5">
 <div class="flex items-center justify-between mb-3">
 <div class="p-2.5 bg-green-100 rounded-lg">
 <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </div>
 </div>
 <div>
 <p class="text-sm text-gray-500 mb-1">الحضور</p>
 <p class="text-2xl font-bold text-gray-900">{{ $progressMetrics['attendance_rate'] }}%</p>
 <p class="text-xs text-gray-500 mt-2">{{ $progressMetrics['session_stats']->present_count ?? 0 }} من {{ $progressMetrics['session_stats']->total_sessions ?? 0 }}</p>
 </div>
 </div>

 <div class="bg-white rounded-lg border border-gray-200 p-5">
 <div class="flex items-center justify-between mb-3">
 <div class="p-2.5 bg-blue-100 rounded-lg">
 <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
 </svg>
 </div>
 </div>
 <div>
 <p class="text-sm text-gray-500 mb-1">آيات جديدة</p>
 <p class="text-2xl font-bold text-gray-900">{{ $progressMetrics['session_stats']->total_new_ayahs ?? 0 }}</p>
 <p class="text-xs text-gray-500 mt-2">محفوظة</p>
 </div>
 </div>

 <div class="bg-white rounded-lg border border-gray-200 p-5">
 <div class="flex items-center justify-between mb-3">
 <div class="p-2.5 bg-purple-100 rounded-lg">
 <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
 </svg>
 </div>
 </div>
 <div>
 <p class="text-sm text-gray-500 mb-1">آيات مراجعة</p>
 <p class="text-2xl font-bold text-gray-900">{{ $progressMetrics['session_stats']->total_revision_ayahs ?? 0 }}</p>
 <p class="text-xs text-gray-500 mt-2">تم مراجعتها</p>
 </div>
 </div>

 <div class="bg-white rounded-lg border border-gray-200 p-5">
 <div class="flex items-center justify-between mb-3">
 <div class="p-2.5 bg-amber-100 rounded-lg">
 <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
 </svg>
 </div>
 </div>
 <div>
 <p class="text-sm text-gray-500 mb-1">متوسط التقييم</p>
 <p class="text-2xl font-bold text-gray-900">{{ $progressMetrics['session_stats']->avg_score ? number_format($progressMetrics['session_stats']->avg_score, 1) : '0.0' }}</p>
 <p class="text-xs text-gray-500 mt-2">من 10</p>
 </div>
 </div>
 </div>
</div>

 {{-- Monthly Progress Chart --}}
@if($progressMetrics['monthly_progress']->isNotEmpty())
<div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-8">
 <div class="p-6 border-b border-gray-200">
 <h3 class="text-lg font-bold text-gray-900">التقدم الشهري</h3>
 <p class="text-sm text-gray-500 mt-1">آيات محفوظة حسب الشهر</p>
 </div>
 <div class="p-6">
 <div class="h-[320px]">
 <canvas id="monthlyProgressChart"></canvas>
 </div>
 </div>
</div>
@endif

 {{-- Recent Sessions Table --}}
<div class="bg-white rounded-xl border border-gray-200 shadow-sm">
 <div class="p-6 border-b border-gray-200">
 <h3 class="text-lg font-bold text-gray-900">آخر الجلسات</h3>
 <p class="text-sm text-gray-500 mt-1">تسجيل الجلسات حسب التاريخ</p>
 </div>
 <div class="p-6">
 @if($student->sessions->isNotEmpty())
 <x-table>
 <x-table.head>
 <x-table.heading>التاريخ</x-table.heading>
 <x-table.heading>النوع</x-table.heading>
 <x-table.heading>الحضور</x-table.heading>
 <x-table.heading>الآيات</x-table.heading>
 <x-table.heading>التقييم</x-table.heading>
 <x-table.heading>ملاحظات</x-table.heading>
 </x-table.head>
 <x-table.body>
 @foreach($student->sessions as $session)
 <x-table.row>
 <x-table.cell>{{ $session->date?->format('Y-m-d') }}</x-table.cell>
 <x-table.cell>
 @if($session->session_type === 'new')
 <x-badge variant="success">حفظ جديد</x-badge>
 @elseif($session->session_type === 'revision')
 <x-badge variant="primary">مراجعة</x-badge>
 @else
 <x-badge variant="info">تأسيس</x-badge>
 @endif
 </x-table.cell>
 <x-table.cell>
 @if($session->attendance_status === 'present')
 <x-badge variant="success">حاضر</x-badge>
 @elseif($session->attendance_status === 'absent')
 <x-badge variant="danger">غائب</x-badge>
 @else
 <x-badge variant="warning">معتذر</x-badge>
 @endif
 </x-table.cell>
 <x-table.cell>
 @if($session->session_type !== 'foundation')
 {{ $session->ayah_count ?? 0 }}
 @else
 -
 @endif
 </x-table.cell>
 <x-table.cell>
 @if($session->attendance_status === 'present' && $session->score)
 <div class="flex items-center gap-2">
 <span class="font-bold {{ $session->score >= 8 ? 'text-green-600' : ($session->score >= 6 ? 'text-amber-600' : 'text-red-600') }}">
 {{ $session->score }}/10
 </span>
 <div class="w-12 bg-gray-200 rounded-full h-1.5">
 <div class="h-1.5 rounded-full {{ $session->score >= 8 ? 'bg-green-500' : ($session->score >= 6 ? 'bg-amber-500' : 'bg-red-500') }}" style="width: {{ ($session->score / 10) * 100 }}%"></div>
 </div>
 </div>
 @else
 -
 @endif
 </x-table.cell>
 <x-table.cell class="text-gray-600 text-xs max-w-xs truncate">{{ $session->notes ?? '-' }}</x-table.cell>
 </x-table.row>
 @endforeach
 </x-table.body>
 </x-table>
 @else
 <div class="text-center py-12">
 <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
 <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </div>
 <p class="text-base text-gray-900 font-semibold mb-1">لا توجد جلسات</p>
 <p class="text-sm text-gray-500">لم يتم تسجيل جلسات للطالب في هذه الفترة</p>
 </div>
 @endif
 </div>
</div>

 {{-- Chart.js for Monthly Progress --}}
@if($progressMetrics['monthly_progress']->isNotEmpty())
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
 Chart.defaults.color = '#64748b';
 Chart.defaults.font.family = "'Tajawal', sans-serif";

 const ctx = document.getElementById('monthlyProgressChart');
 const data = @json($progressMetrics['monthly_progress']);

 const labels = data.map(function(item) {
 const date = new Date(item.month);
 return date.toLocaleDateString('ar-SA', { year: 'numeric', month: 'short' });
 });

 const ayahs = data.map(function(item) {
 return item.ayahs || 0;
 });

 new Chart(ctx, {
 type: 'bar',
 data: {
 labels: labels,
 datasets: [{
 label: 'آيات محفوظة',
 data: ayahs,
 backgroundColor: 'rgba(147, 51, 234, 0.7)',
 borderColor: 'rgb(147, 51, 234)',
 borderWidth: 2,
 borderRadius: 8,
 barThickness: 40
 }]
 },
 options: {
 responsive: true,
 maintainAspectRatio: false,
 plugins: {
 legend: {
 display: false
 },
 tooltip: {
 backgroundColor: 'rgba(15, 23, 42, 0.9)',
 titleColor: '#fff',
 bodyColor: '#fff',
 padding: 12,
 cornerRadius: 8
 }
 },
 scales: {
 y: {
 beginAtZero: true,
 title: {
 display: true,
 text: 'عدد الآيات',
 font: { size: 12 }
 },
 grid: {
 color: '#f1f5f9'
 }
 },
 x: {
 grid: {
 display: false
 }
 }
 }
 }
 });
});
</script>
@endif
@endsection
