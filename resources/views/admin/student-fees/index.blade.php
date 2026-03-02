@extends('layouts.app')

@section('title', 'إدارة الرسوم - Tilawa')

@section('content')
<div class="min-h-screen p-6 pattern-subtle">
    {{-- Islamic Header - رأس الصفحة الإسلامي --}}
    <div class="mb-8 animate-fadeInUp">
        <div class="flex items-center justify-between mb-2">
            <h1 class="text-3xl font-bold heading-islamic text-shadow-emerald">
                إدارة الرسوم الدراسية
            </h1>
            <x-button
                variant="gold"
                href="{{ route('admin.student-fees.create') }}"
                class="gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                تحديد رسوم جديدة
            </x-button>
        </div>
        <p class="text-primary-600 subheading-islamic">
            إدارة ومتابعة رسوم الطلاب الشهرية
        </p>
    </div>

    {{-- Statistics Cards - بطاقات الإحصائيات الإسلامية --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 animate-fadeInUp stagger-1">
        {{-- Total Students Card --}}
        <x-card islamic class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 mb-1">إجمالي الطلاب</p>
                    <h3 class="text-3xl font-bold text-primary-700">{{ $statistics['total_students'] }}</h3>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white shadow-lg shadow-primary-500/30">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
            <x-progress
                :value="$statistics['students_with_fees']"
                :max="$statistics['total_students']"
                variant="primary"
                size="sm"
                class="mt-4"
            />
            <p class="text-xs text-slate-500 mt-2">
                {{ $statistics['students_with_fees'] }} طالب لديهم رسوم محددة
            </p>
        </x-card>

        {{-- Students with Fees --}}
        <x-card islamic class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 mb-1">لديهم رسوم</p>
                    <h3 class="text-3xl font-bold text-gold-600">{{ $statistics['students_with_fees'] }}</h3>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-gold-500 to-gold-600 flex items-center justify-center text-white shadow-lg shadow-gold-500/30">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 text-sm">
                <span class="text-gold-700 font-semibold">{{ round($statistics['coverage_rate'], 1) }}%</span>
                <span class="text-slate-600"> نسبة التغطية</span>
            </div>
        </x-card>

        {{-- Average Fee --}}
        <x-card islamic class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 mb-1">متوسط الرسوم</p>
                    <h3 class="text-3xl font-bold text-accent-600">{{ number_format($statistics['average_fee'], 0) }}</h3>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-accent-500 to-accent-600 flex items-center justify-center text-white shadow-lg shadow-accent-500/30">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-slate-500 mt-4">ريال سعودي / شهرياً</p>
        </x-card>

        {{-- Total Revenue --}}
        <x-card islamic class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 mb-1">الإيراد المتوقع</p>
                    <h3 class="text-3xl font-bold text-success-600">{{ number_format($statistics['total_monthly_revenue'], 0) }}</h3>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-success-500 to-success-600 flex items-center justify-center text-white shadow-lg shadow-success-500/30 glow-emerald">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-slate-500 mt-4">ريال / شهرياً</p>
        </x-card>
    </div>

    {{-- Filters & Search - الفلاتر والبحث --}}
    <x-card variant="primary" class="p-6 mb-6 animate-fadeInUp stagger-2">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- Search --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">بحث</label>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="اسم الطالب أو رقم الهاتف..."
                    class="w-full px-4 py-2.5 border border-primary-200 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-gold-500 transition-all"
                >
            </div>

            {{-- Group Filter --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">المجموعة</label>
                <select name="group" class="w-full px-4 py-2.5 border border-primary-200 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-gold-500">
                    <option value="">الكل</option>
                    <option value="men" {{ request('group') == 'men' ? 'selected' : '' }}>رجال</option>
                    <option value="women" {{ request('group') == 'women' ? 'selected' : '' }}>نساء</option>
                    <option value="kids" {{ request('group') == 'kids' ? 'selected' : '' }}>أطفال</option>
                </select>
            </div>

            {{-- Has Fee Filter --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">حالة الرسوم</label>
                <select name="has_fee" class="w-full px-4 py-2.5 border border-primary-200 rounded-xl focus:ring-2 focus:ring-gold-500 focus:border-gold-500">
                    <option value="">الكل</option>
                    <option value="yes" {{ request('has_fee') == 'yes' ? 'selected' : '' }}>لديهم رسوم</option>
                    <option value="no" {{ request('has_fee') == 'no' ? 'selected' : '' }}>بدون رسوم</option>
                </select>
            </div>

            {{-- Submit --}}
            <div class="flex items-end gap-2">
                <x-button type="submit" variant="primary" class="flex-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    بحث
                </x-button>
                @if(request()->hasAny(['search', 'group', 'has_fee']))
                    <x-button variant="ghost" href="{{ route('admin.student-fees.index') }}">
                        مسح
                    </x-button>
                @endif
            </div>
        </form>
    </x-card>

    {{-- Students Table - جدول الطلاب الإسلامي --}}
    <x-card islamic class="overflow-hidden animate-fadeInUp stagger-3">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-primary-50 to-gold-50 border-b-2 border-gold-300">
                    <tr>
                        <th class="px-6 py-4 text-right text-sm font-bold text-primary-800">الطالب</th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-primary-800">المجموعة</th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-primary-800">الفصل</th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-primary-800">الرسوم الشهرية</th>
                        <th class="px-6 py-4 text-right text-sm font-bold text-primary-800">تاريخ البدء</th>
                        <th class="px-6 py-4 text-center text-sm font-bold text-primary-800">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary-100">
                    @forelse($students as $student)
                        <tr class="hover:bg-primary-50/50 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900">{{ $student->name }}</div>
                                <div class="text-sm text-slate-500">{{ $student->student_phone ?? $student->parent_phone }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $student->group === 'men' ? 'bg-primary-100 text-primary-700' : '' }}
                                    {{ $student->group === 'women' ? 'bg-gold-100 text-gold-700' : '' }}
                                    {{ $student->group === 'kids' ? 'bg-accent-100 text-accent-700' : '' }}
                                ">
                                    {{ $student->group === 'men' ? 'رجال' : '' }}
                                    {{ $student->group === 'women' ? 'نساء' : '' }}
                                    {{ $student->group === 'kids' ? 'أطفال' : '' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-700">
                                {{ $student->class?->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($student->activeFee)
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg font-bold text-gold-600">{{ number_format($student->activeFee->monthly_fee, 0) }}</span>
                                        <span class="text-sm text-slate-500">ريال</span>
                                    </div>
                                @else
                                    <span class="text-warning-600 font-medium">لم يتم تحديد الرسوم</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $student->activeFee?->effective_from?->format('Y/m/d') ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    @if($student->activeFee)
                                        <x-button
                                            variant="ghost"
                                            size="sm"
                                            href="{{ route('admin.student-fees.edit', $student->activeFee) }}"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            تعديل
                                        </x-button>
                                    @else
                                        <x-button
                                            variant="gold"
                                            size="sm"
                                            href="{{ route('admin.student-fees.create', ['student_id' => $student->id]) }}"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            تحديد رسوم
                                        </x-button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-lg font-semibold">لا توجد نتائج</p>
                                    <p class="text-sm">جرب تغيير معايير البحث</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($students->hasPages())
            <div class="px-6 py-4 border-t border-primary-100">
                {{ $students->links() }}
            </div>
        @endif
    </x-card>
</div>
@endsection
