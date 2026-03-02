@extends('layouts.app')

@section('title', 'تفاصيل الدفعة - Tilawa')

@section('content')
<div class="min-h-screen p-4 md:p-6 pattern-subtle">
    {{-- Islamic Header --}}
    <div class="mb-6 md:mb-8 animate-fadeInUp">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
            <div class="flex items-center gap-3 md:gap-4">
                <x-button variant="ghost" href="{{ route('admin.payments.index') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                    </svg>
                    رجوع
                </x-button>
                <h1 class="text-2xl md:text-3xl font-bold heading-islamic text-shadow-emerald">
                    <svg class="w-7 h-7 md:w-9 md:h-9 inline-block ml-2 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    تفاصيل الدفعة
                </h1>
            </div>
            <div class="flex gap-2">
                @if($payment->payment_status !== 'paid')
                    <x-button variant="gold" href="{{ route('admin.payments.edit', $payment) }}" class="justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span class="hidden sm:inline">تعديل</span>
                    </x-button>
                @endif
                <x-button variant="primary" class="justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span class="hidden sm:inline">تحميل الإيصال</span>
                </x-button>
            </div>
        </div>
        <p class="text-primary-600 subheading-islamic">عرض تفاصيل الدفعة ورقم الإيصال</p>
    </div>

    <div class="max-w-4xl mx-auto space-y-6">
        {{-- Payment Status --}}
        <x-card islamic class="p-6 md:p-8 animate-fadeInUp stagger-1">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-xl font-bold text-primary-800 mb-2">حالة الدفعة</h2>
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
                    <span class="inline-flex px-4 py-2 rounded-full text-sm font-semibold {{ $statusColors[$payment->payment_status] ?? '' }}">
                        {{ $statusLabels[$payment->payment_status] ?? $payment->payment_status }}
                    </span>
                </div>
                @if($payment->receipt_number)
                    <div class="text-center sm:text-right">
                        <p class="text-sm text-slate-600 mb-1">رقم الإيصال</p>
                        <p class="text-2xl font-bold text-gold-600 font-mono">{{ $payment->receipt_number }}</p>
                    </div>
                @endif
            </div>

            {{-- Payment Summary --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 md:gap-6">
                <div class="bg-gradient-to-br from-primary-50 to-primary-100 p-4 md:p-6 rounded-xl border border-primary-200">
                    <p class="text-sm text-primary-700 mb-2">المبلغ المستحق</p>
                    <p class="text-2xl md:text-3xl font-bold text-primary-800">{{ number_format($payment->amount_due, 2) }}</p>
                    <p class="text-xs text-primary-600 mt-1">ريال سعودي</p>
                </div>
                <div class="bg-gradient-to-br from-success-50 to-success-100 p-4 md:p-6 rounded-xl border border-success-200">
                    <p class="text-sm text-success-700 mb-2">المبلغ المدفوع</p>
                    <p class="text-2xl md:text-3xl font-bold text-success-800">{{ number_format($payment->amount_paid, 2) }}</p>
                    <p class="text-xs text-success-600 mt-1">ريال سعودي</p>
                </div>
                <div class="bg-gradient-to-br from-gold-50 to-gold-100 p-4 md:p-6 rounded-xl border border-gold-200">
                    <p class="text-sm text-gold-700 mb-2">المتبقي</p>
                    <p class="text-2xl md:text-3xl font-bold text-gold-800">{{ number_format($payment->remaining_amount, 2) }}</p>
                    <p class="text-xs text-gold-600 mt-1">ريال سعودي</p>
                </div>
            </div>
        </x-card>

        {{-- Student & Payment Details --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Student Info --}}
            <x-card islamic class="p-6 animate-fadeInUp stagger-2">
                <h3 class="text-lg font-bold text-primary-800 mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    معلومات الطالب
                </h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-slate-600">الاسم</p>
                        <p class="font-semibold text-slate-900">{{ $payment->student->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600">رقم الهاتف</p>
                        <p class="font-semibold text-slate-900">{{ $payment->student->student_phone ?? $payment->student->parent_phone }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600">المجموعة</p>
                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold
                            {{ $payment->student->group === 'men' ? 'bg-primary-100 text-primary-700' : '' }}
                            {{ $payment->student->group === 'women' ? 'bg-gold-100 text-gold-700' : '' }}
                            {{ $payment->student->group === 'kids' ? 'bg-accent-100 text-accent-700' : '' }}
                        ">
                            {{ $payment->student->group === 'men' ? 'رجال' : '' }}
                            {{ $payment->student->group === 'women' ? 'نساء' : '' }}
                            {{ $payment->student->group === 'kids' ? 'أطفال' : '' }}
                        </span>
                    </div>
                </div>
            </x-card>

            {{-- Payment Info --}}
            <x-card islamic class="p-6 animate-fadeInUp stagger-3">
                <h3 class="text-lg font-bold text-primary-800 mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    معلومات الدفع
                </h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-slate-600">الشهر</p>
                        <p class="font-semibold text-slate-900">{{ $payment->payment_month->format('Y/m') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600">تاريخ الدفع</p>
                        <p class="font-semibold text-slate-900">{{ $payment->payment_date?->format('Y/m/d') ?? 'لم يتم الدفع بعد' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600">طريقة الدفع</p>
                        @php
                            $methodLabels = [
                                'cash' => 'نقدي',
                                'bank_transfer' => 'تحويل بنكي',
                                'card' => 'بطاقة',
                                'other' => 'أخرى'
                            ];
                        @endphp
                        <p class="font-semibold text-slate-900">{{ $methodLabels[$payment->payment_method] ?? $payment->payment_method ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600">تاريخ الإنشاء</p>
                        <p class="font-semibold text-slate-900">{{ $payment->created_at->format('Y/m/d H:i') }}</p>
                    </div>
                </div>
            </x-card>
        </div>

        {{-- Notes --}}
        @if($payment->notes)
            <x-card variant="info" class="p-6 animate-fadeInUp stagger-4">
                <h3 class="text-lg font-bold text-accent-800 mb-3 flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                    </svg>
                    ملاحظات
                </h3>
                <p class="text-slate-700">{{ $payment->notes }}</p>
            </x-card>
        @endif

        {{-- Payment History for This Student --}}
        @if($studentPayments->count() > 1)
            <x-card islamic class="overflow-hidden animate-fadeInUp stagger-5">
                <div class="p-6 border-b border-primary-100">
                    <h3 class="text-lg font-bold text-primary-800 flex items-center gap-2">
                        <svg class="w-6 h-6 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        سجل المدفوعات للطالب
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-primary-50 to-gold-50 border-b-2 border-gold-300">
                            <tr>
                                <th class="px-4 md:px-6 py-4 text-right text-sm font-bold text-primary-800">الشهر</th>
                                <th class="px-4 md:px-6 py-4 text-right text-sm font-bold text-primary-800 hidden sm:table-cell">المبلغ</th>
                                <th class="px-4 md:px-6 py-4 text-center text-sm font-bold text-primary-800">الحالة</th>
                                <th class="px-4 md:px-6 py-4 text-center text-sm font-bold text-primary-800 hidden md:table-cell">التاريخ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-primary-100">
                            @foreach($studentPayments as $historyPayment)
                                <tr class="hover:bg-primary-50/50 transition-colors {{ $historyPayment->id === $payment->id ? 'bg-gold-50' : '' }}">
                                    <td class="px-4 md:px-6 py-4">
                                        <div class="font-semibold text-slate-900">{{ $historyPayment->payment_month->format('Y/m') }}</div>
                                        @if($historyPayment->id === $payment->id)
                                            <span class="text-xs text-gold-600">(الدفعة الحالية)</span>
                                        @endif
                                    </td>
                                    <td class="px-4 md:px-6 py-4 hidden sm:table-cell">
                                        <div class="text-sm">
                                            <div class="font-bold text-gold-600">{{ number_format($historyPayment->amount_paid, 0) }}</div>
                                            <div class="text-xs text-slate-500">من {{ number_format($historyPayment->amount_due, 0) }}</div>
                                        </div>
                                    </td>
                                    <td class="px-4 md:px-6 py-4 text-center">
                                        <span class="px-2 md:px-3 py-1 rounded-full text-xs font-semibold {{ $statusColors[$historyPayment->payment_status] ?? '' }}">
                                            {{ $statusLabels[$historyPayment->payment_status] ?? $historyPayment->payment_status }}
                                        </span>
                                    </td>
                                    <td class="px-4 md:px-6 py-4 text-center text-sm text-slate-600 hidden md:table-cell">
                                        {{ $historyPayment->payment_date?->format('Y/m/d') ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-card>
        @endif
    </div>
</div>
@endsection
