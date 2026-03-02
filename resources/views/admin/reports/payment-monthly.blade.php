@extends('layouts.app')

@section('title', 'التقرير الشهري للمدفوعات - Tilawa')

@section('content')
<div class="min-h-screen p-4 md:p-6 pattern-subtle">
    {{-- Islamic Header --}}
    <div class="mb-6 md:mb-8 animate-fadeInUp">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-2">
            <h1 class="text-2xl md:text-3xl font-bold heading-islamic text-shadow-emerald">
                <svg class="w-8 h-8 md:w-10 md:h-10 inline-block ml-2 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                التقرير الشهري للمدفوعات
            </h1>
            <div class="flex gap-2">
                <x-button variant="gold" class="justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span class="hidden sm:inline">تصدير PDF</span>
                </x-button>
                <x-button variant="secondary" href="{{ route('admin.payments.index') }}" class="justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                    </svg>
                    <span class="hidden sm:inline">رجوع</span>
                </x-button>
            </div>
        </div>
        <p class="text-primary-600 subheading-islamic">إحصائيات التحصيل الشهري والأداء المالي</p>
    </div>

    {{-- Month Selector --}}
    <x-card variant="primary" class="p-4 md:p-6 mb-6 animate-fadeInUp stagger-1">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">الشهر</label>
                <input type="month" name="month" value="{{ request('month', date('Y-m')) }}" class="w-full px-4 py-2.5 border border-primary-200 rounded-xl focus:ring-2 focus:ring-gold-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">المجموعة</label>
                <select name="group" class="w-full px-4 py-2.5 border border-primary-200 rounded-xl focus:ring-2 focus:ring-gold-500">
                    <option value="">الكل</option>
                    <option value="men" {{ request('group') == 'men' ? 'selected' : '' }}>رجال</option>
                    <option value="women" {{ request('group') == 'women' ? 'selected' : '' }}>نساء</option>
                    <option value="kids" {{ request('group') == 'kids' ? 'selected' : '' }}>أطفال</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <x-button type="submit" variant="gold" class="flex-1 justify-center">عرض التقرير</x-button>
            </div>
        </form>
    </x-card>

    {{-- Summary Statistics --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 animate-fadeInUp stagger-2">
        {{-- Expected Revenue --}}
        <x-card islamic class="p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 mb-1">الإيراد المتوقع</p>
                    <h3 class="text-2xl md:text-3xl font-bold text-primary-700">{{ number_format($statistics['expected_revenue'], 0) }}</h3>
                </div>
                <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white shadow-lg shadow-primary-500/30">
                    <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-slate-500 mt-3">ريال سعودي</p>
        </x-card>

        {{-- Collected Amount --}}
        <x-card islamic class="p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 mb-1">المحصل فعلياً</p>
                    <h3 class="text-2xl md:text-3xl font-bold text-success-600">{{ number_format($statistics['collected_amount'], 0) }}</h3>
                </div>
                <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-gradient-to-br from-success-500 to-success-600 flex items-center justify-center text-white shadow-lg shadow-success-500/30">
                    <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-slate-500 mt-3">ريال سعودي</p>
        </x-card>

        {{-- Collection Rate --}}
        <x-card islamic class="p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 mb-1">نسبة التحصيل</p>
                    <h3 class="text-2xl md:text-3xl font-bold text-gold-600">{{ number_format($statistics['collection_rate'], 1) }}%</h3>
                </div>
                <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-gradient-to-br from-gold-500 to-gold-600 flex items-center justify-center text-white shadow-lg shadow-gold-500/30">
                    <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
            </div>
            <x-progress
                :value="$statistics['collection_rate']"
                :max="100"
                variant="emerald-gold"
                size="sm"
                class="mt-3"
            />
        </x-card>

        {{-- Remaining Amount --}}
        <x-card islamic class="p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 mb-1">المتبقي</p>
                    <h3 class="text-2xl md:text-3xl font-bold text-warning-600">{{ number_format($statistics['remaining_amount'], 0) }}</h3>
                </div>
                <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-gradient-to-br from-warning-500 to-warning-600 flex items-center justify-center text-white shadow-lg shadow-warning-500/30">
                    <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-slate-500 mt-3">ريال سعودي</p>
        </x-card>
    </div>

    {{-- Payment Status Breakdown --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Status Cards --}}
        <x-card islamic class="p-6 animate-fadeInUp stagger-3">
            <h3 class="text-lg font-bold text-primary-800 mb-4 flex items-center gap-2">
                <svg class="w-6 h-6 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                توزيع الحالات
            </h3>
            <div class="space-y-4">
                {{-- Paid --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-slate-700">مدفوع بالكامل</span>
                        <span class="text-sm font-bold text-success-600">{{ $statusBreakdown['paid']['count'] }} ({{ number_format($statusBreakdown['paid']['amount'], 0) }} ريال)</span>
                    </div>
                    <x-progress :value="$statusBreakdown['paid']['count']" :max="$statistics['total_payments']" variant="success" size="sm" />
                </div>

                {{-- Partial --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-slate-700">دفع جزئي</span>
                        <span class="text-sm font-bold text-accent-600">{{ $statusBreakdown['partial']['count'] }} ({{ number_format($statusBreakdown['partial']['amount'], 0) }} ريال)</span>
                    </div>
                    <x-progress :value="$statusBreakdown['partial']['count']" :max="$statistics['total_payments']" variant="info" size="sm" />
                </div>

                {{-- Pending --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-slate-700">معلق</span>
                        <span class="text-sm font-bold text-warning-600">{{ $statusBreakdown['pending']['count'] }} ({{ number_format($statusBreakdown['pending']['amount'], 0) }} ريال)</span>
                    </div>
                    <x-progress :value="$statusBreakdown['pending']['count']" :max="$statistics['total_payments']" variant="warning" size="sm" />
                </div>

                {{-- Overdue --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-slate-700">متأخر</span>
                        <span class="text-sm font-bold text-error-600">{{ $statusBreakdown['overdue']['count'] }} ({{ number_format($statusBreakdown['overdue']['amount'], 0) }} ريال)</span>
                    </div>
                    <x-progress :value="$statusBreakdown['overdue']['count']" :max="$statistics['total_payments']" variant="error" size="sm" />
                </div>
            </div>
        </x-card>

        {{-- Payment Methods --}}
        <x-card islamic class="p-6 animate-fadeInUp stagger-4">
            <h3 class="text-lg font-bold text-primary-800 mb-4 flex items-center gap-2">
                <svg class="w-6 h-6 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                طرق الدفع
            </h3>
            <div class="space-y-4">
                @foreach(['cash' => 'نقدي', 'bank_transfer' => 'تحويل بنكي', 'card' => 'بطاقة', 'other' => 'أخرى'] as $method => $label)
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-slate-700">{{ $label }}</span>
                            <span class="text-sm font-bold text-primary-600">
                                {{ $paymentMethods[$method]['count'] ?? 0 }}
                                ({{ number_format($paymentMethods[$method]['amount'] ?? 0, 0) }} ريال)
                            </span>
                        </div>
                        <x-progress
                            :value="$paymentMethods[$method]['count'] ?? 0"
                            :max="$statistics['paid_count']"
                            variant="primary"
                            size="sm"
                        />
                    </div>
                @endforeach
            </div>
        </x-card>
    </div>

    {{-- Detailed Payments Table --}}
    <x-card islamic class="overflow-hidden animate-fadeInUp stagger-5">
        <div class="p-6 border-b border-primary-100">
            <h3 class="text-lg font-bold text-primary-800 flex items-center gap-2">
                <svg class="w-6 h-6 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                تفاصيل المدفوعات
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-primary-50 to-gold-50 border-b-2 border-gold-300">
                    <tr>
                        <th class="px-4 md:px-6 py-4 text-right text-sm font-bold text-primary-800">الطالب</th>
                        <th class="px-4 md:px-6 py-4 text-right text-sm font-bold text-primary-800 hidden md:table-cell">المجموعة</th>
                        <th class="px-4 md:px-6 py-4 text-right text-sm font-bold text-primary-800">المبلغ</th>
                        <th class="px-4 md:px-6 py-4 text-center text-sm font-bold text-primary-800">الحالة</th>
                        <th class="px-4 md:px-6 py-4 text-center text-sm font-bold text-primary-800 hidden lg:table-cell">طريقة الدفع</th>
                        <th class="px-4 md:px-6 py-4 text-center text-sm font-bold text-primary-800 hidden sm:table-cell">التاريخ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary-100">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-primary-50/50 transition-colors">
                            <td class="px-4 md:px-6 py-4">
                                <div class="font-semibold text-slate-900">{{ $payment->student->name }}</div>
                                <div class="text-xs text-slate-500">{{ $payment->student->student_phone ?? $payment->student->parent_phone }}</div>
                            </td>
                            <td class="px-4 md:px-6 py-4 hidden md:table-cell">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    {{ $payment->student->group === 'men' ? 'bg-primary-100 text-primary-700' : '' }}
                                    {{ $payment->student->group === 'women' ? 'bg-gold-100 text-gold-700' : '' }}
                                    {{ $payment->student->group === 'kids' ? 'bg-accent-100 text-accent-700' : '' }}
                                ">
                                    {{ $payment->student->group === 'men' ? 'رجال' : '' }}
                                    {{ $payment->student->group === 'women' ? 'نساء' : '' }}
                                    {{ $payment->student->group === 'kids' ? 'أطفال' : '' }}
                                </span>
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
                            <td class="px-4 md:px-6 py-4 text-center text-sm text-slate-600 hidden lg:table-cell">
                                @php
                                    $methodLabels = [
                                        'cash' => 'نقدي',
                                        'bank_transfer' => 'تحويل بنكي',
                                        'card' => 'بطاقة',
                                        'other' => 'أخرى'
                                    ];
                                @endphp
                                {{ $methodLabels[$payment->payment_method] ?? '-' }}
                            </td>
                            <td class="px-4 md:px-6 py-4 text-center text-sm text-slate-600 hidden sm:table-cell">
                                {{ $payment->payment_date?->format('Y/m/d') ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center text-slate-400">
                                    <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-lg font-semibold">لا توجد مدفوعات لهذا الشهر</p>
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
