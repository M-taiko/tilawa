@extends('layouts.app')

@section('title', 'المدفوعات - Tilawa')

@section('content')
<div class="min-h-screen p-4 md:p-6 pattern-subtle">
    {{-- Islamic Header --}}
    <div class="mb-6 md:mb-8 animate-fadeInUp">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-2">
            <h1 class="text-2xl md:text-3xl font-bold heading-islamic text-shadow-emerald">
                <svg class="w-8 h-8 md:w-10 md:h-10 inline-block ml-2 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                إدارة المدفوعات
            </h1>
            <x-button variant="primary" href="{{ route('admin.payments.create') }}" class="w-full sm:w-auto justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                تسجيل دفعة
            </x-button>
        </div>
        <p class="text-primary-600 subheading-islamic">متابعة وتسجيل مدفوعات الطلاب</p>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6 animate-fadeInUp stagger-1">
        <x-button variant="gold" href="{{ route('admin.reports.payments.monthly') }}" class="justify-center py-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span class="hidden sm:inline">التقرير الشهري</span>
        </x-button>

        <x-button variant="warning" href="{{ route('admin.reports.payments.overdue') }}" class="justify-center py-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="hidden sm:inline">المتأخرات</span>
        </x-button>

        <form action="{{ route('admin.payments.generate-monthly') }}" method="POST" class="contents">
            @csrf
            <input type="hidden" name="month" value="{{ date('Y-m') }}">
            <x-button type="submit" variant="success" class="justify-center py-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span class="hidden sm:inline">توليد شهري</span>
            </x-button>
        </form>

        <x-button variant="secondary" href="{{ route('admin.student-fees.index') }}" class="justify-center py-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="hidden sm:inline">إدارة الرسوم</span>
        </x-button>
    </div>

    {{-- Filters --}}
    <x-card variant="primary" class="p-4 md:p-6 mb-6 animate-fadeInUp stagger-2">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">الحالة</label>
                <select name="status" class="w-full px-4 py-2.5 border border-primary-200 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-gold-500">
                    <option value="">الكل</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلق</option>
                    <option value="partial" {{ request('status') == 'partial' ? 'selected' : '' }}>جزئي</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>مدفوع</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>متأخر</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">الشهر</label>
                <input type="month" name="month" value="{{ request('month') }}" class="w-full px-4 py-2.5 border border-primary-200 rounded-xl focus:ring-2 focus:ring-gold-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">بحث</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="اسم الطالب..." class="w-full px-4 py-2.5 border border-primary-200 rounded-xl focus:ring-2 focus:ring-gold-500">
            </div>

            <div class="flex items-end gap-2">
                <x-button type="submit" variant="primary" class="flex-1 justify-center">بحث</x-button>
                @if(request()->hasAny(['status', 'month', 'search']))
                    <x-button variant="ghost" href="{{ route('admin.payments.index') }}">مسح</x-button>
                @endif
            </div>
        </form>
    </x-card>

    {{-- Payments Table --}}
    <x-card islamic class="overflow-hidden animate-fadeInUp stagger-3">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-primary-50 to-gold-50 border-b-2 border-gold-300">
                    <tr>
                        <th class="px-4 md:px-6 py-4 text-right text-sm font-bold text-primary-800">الطالب</th>
                        <th class="px-4 md:px-6 py-4 text-right text-sm font-bold text-primary-800 hidden md:table-cell">الشهر</th>
                        <th class="px-4 md:px-6 py-4 text-right text-sm font-bold text-primary-800">المبلغ</th>
                        <th class="px-4 md:px-6 py-4 text-center text-sm font-bold text-primary-800">الحالة</th>
                        <th class="px-4 md:px-6 py-4 text-center text-sm font-bold text-primary-800">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary-100">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-primary-50/50 transition-colors">
                            <td class="px-4 md:px-6 py-4">
                                <div class="font-semibold text-slate-900 text-sm md:text-base">{{ $payment->student->name }}</div>
                            </td>
                            <td class="px-4 md:px-6 py-4 hidden md:table-cell text-slate-700">
                                {{ $payment->payment_month->format('Y/m') }}
                            </td>
                            <td class="px-4 md:px-6 py-4">
                                <div class="text-sm">
                                    <div class="font-bold text-gold-600">{{ number_format($payment->amount_paid, 0) }}</div>
                                    <div class="text-xs text-slate-500">من {{ number_format($payment->amount_due, 0) }}</div>
                                </div>
                            </td>
                            <td class="px-4 md:px-6 py-4 text-center">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-warning-100 text-warning-700',
                                        'partial' => 'bg-accent-100 text-accent-700',
                                        'paid' => 'bg-success-100 text-success-700',
                                        'overdue' => 'bg-error-100 text-error-700',
                                    ];
                                    $statusLabels = [
                                        'pending' => 'معلق',
                                        'partial' => 'جزئي',
                                        'paid' => 'مدفوع',
                                        'overdue' => 'متأخر',
                                    ];
                                @endphp
                                <span class="px-2 md:px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$payment->payment_status] ?? '' }}">
                                    {{ $statusLabels[$payment->payment_status] ?? $payment->payment_status }}
                                </span>
                            </td>
                            <td class="px-4 md:px-6 py-4 text-center">
                                <x-button variant="ghost" size="sm" href="{{ route('admin.payments.show', $payment) }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <span class="hidden sm:inline">عرض</span>
                                </x-button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center text-slate-400">
                                    <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-lg font-semibold">لا توجد مدفوعات</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payments->hasPages())
            <div class="px-4 md:px-6 py-4 border-t border-primary-100">
                {{ $payments->links() }}
            </div>
        @endif
    </x-card>
</div>
@endsection
