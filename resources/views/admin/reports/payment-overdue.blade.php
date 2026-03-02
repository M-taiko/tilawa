@extends('layouts.app')

@section('title', 'تقرير المتأخرات - Tilawa')

@section('content')
<div class="min-h-screen p-4 md:p-6 pattern-subtle">
    {{-- Islamic Header --}}
    <div class="mb-6 md:mb-8 animate-fadeInUp">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-2">
            <h1 class="text-2xl md:text-3xl font-bold heading-islamic text-shadow-emerald">
                <svg class="w-8 h-8 md:w-10 md:h-10 inline-block ml-2 text-error-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                تقرير المدفوعات المتأخرة
            </h1>
            <div class="flex gap-2">
                <x-button variant="primary" class="justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    <span class="hidden sm:inline">تصدير Excel</span>
                </x-button>
                <x-button variant="secondary" href="{{ route('admin.payments.index') }}" class="justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                    </svg>
                    <span class="hidden sm:inline">رجوع</span>
                </x-button>
            </div>
        </div>
        <p class="text-primary-600 subheading-islamic">عرض جميع المدفوعات المتأخرة والإجراءات المطلوبة</p>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6 animate-fadeInUp stagger-1">
        {{-- Total Overdue --}}
        <x-card islamic class="p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 mb-1">إجمالي المتأخرات</p>
                    <h3 class="text-2xl md:text-3xl font-bold text-error-600">{{ $statistics['total_overdue'] }}</h3>
                </div>
                <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-gradient-to-br from-error-500 to-error-600 flex items-center justify-center text-white shadow-lg shadow-error-500/30">
                    <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-slate-500 mt-3">عدد الدفعات المتأخرة</p>
        </x-card>

        {{-- Total Amount --}}
        <x-card islamic class="p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 mb-1">المبلغ المتأخر</p>
                    <h3 class="text-2xl md:text-3xl font-bold text-gold-600">{{ number_format($statistics['total_amount'], 0) }}</h3>
                </div>
                <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-gradient-to-br from-gold-500 to-gold-600 flex items-center justify-center text-white shadow-lg shadow-gold-500/30">
                    <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-slate-500 mt-3">ريال سعودي</p>
        </x-card>

        {{-- Affected Students --}}
        <x-card islamic class="p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 mb-1">الطلاب المتأخرون</p>
                    <h3 class="text-2xl md:text-3xl font-bold text-warning-600">{{ $statistics['affected_students'] }}</h3>
                </div>
                <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-gradient-to-br from-warning-500 to-warning-600 flex items-center justify-center text-white shadow-lg shadow-warning-500/30">
                    <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-slate-500 mt-3">طالب</p>
        </x-card>

        {{-- Average Delay --}}
        <x-card islamic class="p-4 md:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 mb-1">متوسط التأخير</p>
                    <h3 class="text-2xl md:text-3xl font-bold text-accent-600">{{ $statistics['average_delay_days'] }}</h3>
                </div>
                <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center text-white shadow-lg shadow-accent-500/30">
                    <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-slate-500 mt-3">يوم</p>
        </x-card>
    </div>

    {{-- Filters --}}
    <x-card variant="warning" class="p-4 md:p-6 mb-6 animate-fadeInUp stagger-2">
        <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">بحث</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="اسم الطالب..." class="w-full px-4 py-2.5 border border-warning-200 rounded-xl focus:ring-2 focus:ring-gold-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">فترة التأخير</label>
                <select name="delay_period" class="w-full px-4 py-2.5 border border-warning-200 rounded-xl focus:ring-2 focus:ring-gold-500">
                    <option value="">الكل</option>
                    <option value="week" {{ request('delay_period') == 'week' ? 'selected' : '' }}>أسبوع واحد</option>
                    <option value="month" {{ request('delay_period') == 'month' ? 'selected' : '' }}>شهر واحد</option>
                    <option value="3months" {{ request('delay_period') == '3months' ? 'selected' : '' }}>3 أشهر</option>
                    <option value="6months" {{ request('delay_period') == '6months' ? 'selected' : '' }}>6 أشهر</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">المجموعة</label>
                <select name="group" class="w-full px-4 py-2.5 border border-warning-200 rounded-xl focus:ring-2 focus:ring-gold-500">
                    <option value="">الكل</option>
                    <option value="men" {{ request('group') == 'men' ? 'selected' : '' }}>رجال</option>
                    <option value="women" {{ request('group') == 'women' ? 'selected' : '' }}>نساء</option>
                    <option value="kids" {{ request('group') == 'kids' ? 'selected' : '' }}>أطفال</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <x-button type="submit" variant="warning" class="flex-1 justify-center">بحث</x-button>
                @if(request()->hasAny(['search', 'delay_period', 'group']))
                    <x-button variant="ghost" href="{{ route('admin.reports.payments.overdue') }}">مسح</x-button>
                @endif
            </div>
        </form>
    </x-card>

    {{-- Overdue Payments Table --}}
    <x-card islamic class="overflow-hidden animate-fadeInUp stagger-3">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-error-50 to-warning-50 border-b-2 border-error-300">
                    <tr>
                        <th class="px-4 md:px-6 py-4 text-right text-sm font-bold text-primary-800">الطالب</th>
                        <th class="px-4 md:px-6 py-4 text-right text-sm font-bold text-primary-800 hidden md:table-cell">الشهر</th>
                        <th class="px-4 md:px-6 py-4 text-right text-sm font-bold text-primary-800">المبلغ</th>
                        <th class="px-4 md:px-6 py-4 text-center text-sm font-bold text-primary-800 hidden lg:table-cell">مدة التأخير</th>
                        <th class="px-4 md:px-6 py-4 text-center text-sm font-bold text-primary-800 hidden sm:table-cell">التواصل</th>
                        <th class="px-4 md:px-6 py-4 text-center text-sm font-bold text-primary-800">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary-100">
                    @forelse($overduePayments as $payment)
                        @php
                            $daysOverdue = now()->diffInDays($payment->payment_month);
                            $urgencyClass = $daysOverdue > 60 ? 'bg-error-50 border-r-4 border-error-500' : ($daysOverdue > 30 ? 'bg-warning-50 border-r-4 border-warning-500' : '');
                        @endphp
                        <tr class="hover:bg-primary-50/50 transition-colors {{ $urgencyClass }}">
                            <td class="px-4 md:px-6 py-4">
                                <div class="font-semibold text-slate-900">{{ $payment->student->name }}</div>
                                <div class="text-sm text-slate-500">{{ $payment->student->student_phone ?? $payment->student->parent_phone }}</div>
                            </td>
                            <td class="px-4 md:px-6 py-4 hidden md:table-cell text-slate-700">
                                {{ $payment->payment_month->format('Y/m') }}
                            </td>
                            <td class="px-4 md:px-6 py-4">
                                <div class="text-sm">
                                    <div class="font-bold text-error-600">{{ number_format($payment->remaining_amount, 0) }}</div>
                                    <div class="text-xs text-slate-500">من {{ number_format($payment->amount_due, 0) }} ريال</div>
                                </div>
                            </td>
                            <td class="px-4 md:px-6 py-4 text-center hidden lg:table-cell">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $daysOverdue > 60 ? 'bg-error-100 text-error-700' : '' }}
                                    {{ $daysOverdue > 30 && $daysOverdue <= 60 ? 'bg-warning-100 text-warning-700' : '' }}
                                    {{ $daysOverdue <= 30 ? 'bg-accent-100 text-accent-700' : '' }}
                                ">
                                    {{ $daysOverdue }} يوم
                                </span>
                            </td>
                            <td class="px-4 md:px-6 py-4 text-center hidden sm:table-cell">
                                @if($payment->student->student_phone || $payment->student->parent_phone)
                                    <a href="tel:{{ $payment->student->student_phone ?? $payment->student->parent_phone }}" class="text-primary-600 hover:text-primary-800">
                                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                    </a>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 md:px-6 py-4">
                                <div class="flex items-center justify-center gap-1 md:gap-2">
                                    <x-button variant="ghost" size="sm" href="{{ route('admin.payments.show', $payment) }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span class="hidden lg:inline">عرض</span>
                                    </x-button>
                                    <form method="POST" action="{{ route('admin.payments.send-reminder', $payment) }}" class="inline">
                                        @csrf
                                        <x-button type="submit" variant="warning" size="sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                            </svg>
                                            <span class="hidden lg:inline">تذكير</span>
                                        </x-button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center text-slate-400">
                                    <svg class="w-16 h-16 mb-4 opacity-50 text-success-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-lg font-semibold text-success-600">ممتاز! لا توجد مدفوعات متأخرة</p>
                                    <p class="text-sm text-slate-500 mt-1">جميع الطلاب ملتزمون بالمدفوعات</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($overduePayments->hasPages())
            <div class="px-4 md:px-6 py-4 border-t border-primary-100">
                {{ $overduePayments->links() }}
            </div>
        @endif
    </x-card>

    {{-- Action Tips --}}
    @if($overduePayments->count() > 0)
        <x-card variant="info" class="p-4 md:p-6 mt-6 animate-fadeInUp stagger-4">
            <div class="flex gap-4">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 rounded-xl bg-accent-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <h3 class="font-bold text-accent-800 mb-2">نصائح للمتابعة</h3>
                    <ul class="text-sm text-slate-600 space-y-1">
                        <li>• المدفوعات المتأخرة أكثر من 60 يوماً تحتاج متابعة عاجلة</li>
                        <li>• استخدم زر "تذكير" لإرسال إشعار للطالب</li>
                        <li>• يمكن تصدير التقرير لمتابعة خارجية</li>
                        <li>• تواصل مباشرة عبر الهاتف للحالات العاجلة</li>
                    </ul>
                </div>
            </div>
        </x-card>
    @endif
</div>
@endsection
