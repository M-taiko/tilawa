@extends('layouts.app')

@section('title', 'إدارة المستخدمين')
@section('noindex')@endsection

@section('content')
{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">إدارة المستخدمين</h1>
        <p class="text-sm text-gray-500 mt-1">جميع المستخدمين في النظام — يوزرات، أدوار، ومراكز</p>
    </div>
    <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-lg">
        الإجمالي: {{ $users->total() }} مستخدم
    </span>
</div>

{{-- Search --}}
<form method="GET" action="{{ route('saas.users.index') }}" class="mb-6">
    <div class="flex gap-3 max-w-md">
        <input type="text" name="search" value="{{ $search }}"
               placeholder="ابحث بالاسم أو الإيميل..."
               class="flex-1 px-4 py-2 rounded-xl border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
        <button type="submit"
                class="px-4 py-2 bg-primary-600 text-white rounded-xl text-sm font-semibold hover:bg-primary-700 transition-colors">
            بحث
        </button>
        @if($search)
        <a href="{{ route('saas.users.index') }}"
           class="px-4 py-2 bg-gray-100 text-gray-600 rounded-xl text-sm font-semibold hover:bg-gray-200 transition-colors">
            مسح
        </a>
        @endif
    </div>
</form>

{{-- Table --}}
<x-table>
    <x-table.head>
        <x-table.heading>المستخدم</x-table.heading>
        <x-table.heading>الدور العام</x-table.heading>
        <x-table.heading>المراكز والأدوار</x-table.heading>
        <x-table.heading>الحالة</x-table.heading>
        <x-table.heading>تاريخ التسجيل</x-table.heading>
        <x-table.heading>الإجراءات</x-table.heading>
    </x-table.head>
    <x-table.body>
        @forelse($users as $user)
        <x-table.row>
            {{-- User Info --}}
            <x-table.cell>
                <div class="font-semibold text-gray-900">{{ $user->name }}</div>
                <div class="text-xs text-gray-500 mt-0.5">{{ $user->email }}</div>
            </x-table.cell>

            {{-- Global Role --}}
            <x-table.cell>
                @if($user->global_role === 'saas_admin')
                    <x-badge variant="warning">مدير النظام</x-badge>
                @else
                    <span class="text-xs text-gray-400">—</span>
                @endif
            </x-table.cell>

            {{-- Tenants & Roles --}}
            <x-table.cell>
                @if($user->tenants->isEmpty())
                    <span class="text-xs text-gray-400">لا يوجد مركز</span>
                @else
                    <div class="flex flex-col gap-1">
                        @foreach($user->tenants as $tenant)
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-medium text-gray-700">{{ $tenant->name }}</span>
                            @php $role = $tenant->pivot->role; @endphp
                            @if($role === 'tenant_admin')
                                <x-badge variant="info">مدير مركز</x-badge>
                            @elseif($role === 'teacher')
                                <x-badge variant="success">معلم</x-badge>
                            @else
                                <x-badge>{{ $role }}</x-badge>
                            @endif
                        </div>
                        @endforeach
                    </div>
                @endif
            </x-table.cell>

            {{-- Status --}}
            <x-table.cell>
                @if($user->is_active)
                    <x-badge variant="success">نشط</x-badge>
                @else
                    <x-badge variant="danger">معطل</x-badge>
                @endif
            </x-table.cell>

            {{-- Date --}}
            <x-table.cell class="text-sm text-gray-500">
                {{ $user->created_at->format('Y/m/d') }}
            </x-table.cell>

            {{-- Actions --}}
            <x-table.cell>
                <a href="{{ route('saas.users.edit', $user) }}"
                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-primary-50 text-primary-700 hover:bg-primary-100 text-sm font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    تعديل
                </a>
            </x-table.cell>
        </x-table.row>
        @empty
        <x-table.row>
            <x-table.cell colspan="6" class="text-center text-gray-400 py-8">
                لا يوجد مستخدمون
            </x-table.cell>
        </x-table.row>
        @endforelse
    </x-table.body>
</x-table>

{{-- Pagination --}}
<div class="mt-4">
    {{ $users->links() }}
</div>
@endsection
