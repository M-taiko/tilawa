@extends('layouts.app')

@section('title', 'تعديل مركز')

@section('content')
{{-- Page Header --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
 <div>
 <h1 class="text-3xl font-bold text-gray-900">تعديل بيانات المركز</h1>
 <p class="text-sm text-gray-600 mt-1">تحديث معلومات مركز التحفيظ</p>
 </div>
 <x-button href="{{ route('saas.tenants.index') }}" variant="ghost">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
 </svg>
 رجوع
 </x-button>
</div>

<x-card>
 <x-card-body>
 <form method="POST" action="{{ route('saas.tenants.update', $tenant) }}" class="space-y-6">
 @csrf
 @method('PUT')
 
 <x-input name="tenant_name" label="اسم المركز" placeholder="مثال: مركز النور لتحفيظ القرآن" required value="{{ old('tenant_name', $tenant->name) }}">
 <x-slot name="icon">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
 </svg>
 </x-slot>
 </x-input>

 <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
 <x-button type="submit" variant="primary" size="lg">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
 </svg>
 حفظ التعديلات
 </x-button>
 <x-button href="{{ route('saas.tenants.index') }}" variant="outline" size="lg">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
 </svg>
 إلغاء
 </x-button>
 </div>
 </form>
 </x-card-body>
</x-card>
@endsection
