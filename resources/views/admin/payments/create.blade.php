@extends('layouts.app')

@section('title', 'تسجيل دفعة - Tilawa')

@section('content')
<div class="min-h-screen p-4 md:p-6 pattern-subtle">
    {{-- Islamic Header --}}
    <div class="mb-6 md:mb-8 animate-fadeInUp">
        <div class="flex items-center gap-3 md:gap-4 mb-4">
            <x-button variant="ghost" href="{{ route('admin.payments.index') }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                </svg>
                رجوع
            </x-button>
            <h1 class="text-2xl md:text-3xl font-bold heading-islamic text-shadow-emerald">
                <svg class="w-7 h-7 md:w-9 md:h-9 inline-block ml-2 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                تسجيل دفعة جديدة
            </h1>
        </div>
        <p class="text-primary-600 subheading-islamic">تسجيل دفعة شهرية لطالب</p>
    </div>

    <div class="max-w-3xl mx-auto">
        <x-card islamic class="p-6 md:p-8 animate-fadeInUp stagger-1">
            <form method="POST" action="{{ route('admin.payments.store') }}" class="space-y-6">
                @csrf

                {{-- Payment Selection --}}
                <div>
                    <label class="block text-sm font-bold text-primary-800 mb-3">
                        <span class="text-gold-600">*</span> اختر الطالب والشهر
                    </label>
                    <select
                        name="payment_id"
                        id="payment_id"
                        required
                        class="w-full px-4 py-3 border-2 border-primary-200 rounded-xl focus:ring-4 focus:ring-gold-500/30 focus:border-gold-500 transition-all"
                    >
                        <option value="">-- اختر الدفعة --</option>
                        @foreach($pendingPayments as $payment)
                            <option value="{{ $payment->id }}" data-due="{{ $payment->amount_due }}" data-paid="{{ $payment->amount_paid }}">
                                {{ $payment->student->name }} -
                                {{ $payment->payment_month->format('Y/m') }} -
                                المطلوب: {{ number_format($payment->amount_due - $payment->amount_paid, 2) }} ريال
                            </option>
                        @endforeach
                    </select>
                    @error('payment_id')
                        <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Payment Details Card --}}
                <div id="payment-details" class="hidden">
                    <x-card variant="primary" class="p-4 md:p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                            <div>
                                <p class="text-slate-600 mb-1">المبلغ المستحق</p>
                                <p class="text-xl font-bold text-primary-700" id="amount-due">0</p>
                            </div>
                            <div>
                                <p class="text-slate-600 mb-1">المدفوع سابقاً</p>
                                <p class="text-xl font-bold text-success-600" id="amount-paid">0</p>
                            </div>
                            <div>
                                <p class="text-slate-600 mb-1">المتبقي</p>
                                <p class="text-xl font-bold text-gold-600" id="amount-remaining">0</p>
                            </div>
                        </div>
                    </x-card>
                </div>

                {{-- Amount Paid --}}
                <div>
                    <label class="block text-sm font-bold text-primary-800 mb-3">
                        <span class="text-gold-600">*</span> المبلغ المدفوع
                    </label>
                    <div class="relative">
                        <input
                            type="number"
                            name="amount"
                            id="amount"
                            min="0"
                            step="0.01"
                            required
                            placeholder="500.00"
                            class="w-full px-4 py-3 pr-20 border-2 border-primary-200 rounded-xl focus:ring-4 focus:ring-gold-500/30 focus:border-gold-500 transition-all text-lg font-semibold"
                        >
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-semibold">ريال</span>
                    </div>
                    @error('amount')
                        <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Payment Date --}}
                <div>
                    <label class="block text-sm font-bold text-primary-800 mb-3">
                        <span class="text-gold-600">*</span> تاريخ الدفع
                    </label>
                    <input
                        type="date"
                        name="payment_date"
                        value="{{ date('Y-m-d') }}"
                        required
                        class="w-full px-4 py-3 border-2 border-primary-200 rounded-xl focus:ring-4 focus:ring-gold-500/30 focus:border-gold-500 transition-all"
                    >
                    @error('payment_date')
                        <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Payment Method --}}
                <div>
                    <label class="block text-sm font-bold text-primary-800 mb-3">
                        <span class="text-gold-600">*</span> طريقة الدفع
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach(['cash' => 'نقدي', 'bank_transfer' => 'تحويل بنكي', 'card' => 'بطاقة', 'other' => 'أخرى'] as $method => $label)
                            <label class="relative flex items-center justify-center px-4 py-3 border-2 border-primary-200 rounded-xl cursor-pointer hover:border-gold-500 hover:bg-gold-50 transition-all">
                                <input type="radio" name="payment_method" value="{{ $method }}" {{ $method === 'cash' ? 'checked' : '' }} class="sr-only peer">
                                <span class="text-sm font-semibold text-slate-700 peer-checked:text-gold-700">{{ $label }}</span>
                                <div class="absolute inset-0 border-2 border-gold-500 rounded-xl opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                            </label>
                        @endforeach
                    </div>
                    @error('payment_method')
                        <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Notes --}}
                <div>
                    <label class="block text-sm font-bold text-primary-800 mb-3">ملاحظات</label>
                    <textarea
                        name="notes"
                        rows="3"
                        class="w-full px-4 py-3 border-2 border-primary-200 rounded-xl focus:ring-4 focus:ring-gold-500/30 focus:border-gold-500 transition-all"
                        placeholder="أي ملاحظات إضافية..."
                    ></textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit Buttons --}}
                <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t-2 border-gold-200">
                    <x-button type="submit" variant="gold" class="flex-1 justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        تسجيل الدفعة
                    </x-button>
                    <x-button type="button" variant="secondary" href="{{ route('admin.payments.index') }}" class="justify-center">
                        إلغاء
                    </x-button>
                </div>
            </form>
        </x-card>

        {{-- Help Card --}}
        <x-card variant="info" class="p-4 md:p-6 mt-6 animate-fadeInUp stagger-2">
            <div class="flex gap-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 rounded-xl bg-accent-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="font-bold text-accent-800 mb-2">نصائح هامة</h3>
                    <ul class="text-sm text-slate-600 space-y-1">
                        <li>• يمكن تسجيل دفعات جزئية، سيتم تحديث حالة الدفعة تلقائياً</li>
                        <li>• سيتم إنشاء رقم إيصال تلقائي بعد التسجيل</li>
                        <li>• تأكد من تحديد طريقة الدفع الصحيحة</li>
                    </ul>
                </div>
            </div>
        </x-card>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentSelect = document.getElementById('payment_id');
    const paymentDetails = document.getElementById('payment-details');
    const amountInput = document.getElementById('amount');

    paymentSelect.addEventListener('change', function() {
        const option = this.options[this.selectedIndex];

        if (option.value) {
            const due = parseFloat(option.dataset.due) || 0;
            const paid = parseFloat(option.dataset.paid) || 0;
            const remaining = due - paid;

            document.getElementById('amount-due').textContent = due.toFixed(2) + ' ريال';
            document.getElementById('amount-paid').textContent = paid.toFixed(2) + ' ريال';
            document.getElementById('amount-remaining').textContent = remaining.toFixed(2) + ' ريال';

            paymentDetails.classList.remove('hidden');
            amountInput.value = remaining.toFixed(2);
            amountInput.max = remaining;
        } else {
            paymentDetails.classList.add('hidden');
            amountInput.value = '';
            amountInput.max = '';
        }
    });
});
</script>
@endsection
