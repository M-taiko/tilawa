@extends('layouts.app')

@section('title', 'استيراد الطلاب')

@section('content')
<div class="max-w-5xl mx-auto">
 {{-- Page Header --}}
 <div class="flex items-center justify-between mb-8">
 <div>
 <h1 class="text-3xl font-bold text-gray-900">استيراد الطلاب</h1>
 <p class="text-sm text-gray-500 mt-1">استيراد بيانات الطلاب من ملف Excel أو CSV بسهولة</p>
 </div>
 <x-button href="{{ route('admin.students.index') }}" variant="outline">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
 </svg>
 رجوع للطلاب
 </x-button>
 </div>

 {{-- Success Message --}}
 @if(session('success'))
 <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
 <div class="flex items-start gap-3">
 <div class="p-1 bg-green-100 rounded-lg">
 <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 </div>
 <p class="text-green-800 font-medium">{{ session('success') }}</p>
 </div>
 </div>
 @endif

 {{-- Warning Message --}}
 @if(session('warning'))
 <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
 <div class="flex items-start gap-3">
 <div class="p-1 bg-yellow-100 rounded-lg">
 <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
 </svg>
 </div>
 <div class="flex-1">
 <p class="text-yellow-800 font-medium">{{ session('warning') }}</p>
 @if(session('import_errors'))
 <ul class="mt-2 space-y-1">
 @foreach(session('import_errors') as $error)
 <li class="text-sm text-yellow-700 flex items-start gap-2">
 <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
 <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
 </svg>
 {{ $error }}
 </li>
 @endforeach
 </ul>
 @endif
 </div>
 </div>
 </div>
 @endif

 <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
 {{-- Upload Section --}}
 <div class="lg:col-span-2 space-y-6">
 {{-- Upload Form --}}
 <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
 <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
 <div class="flex items-center gap-3">
 <div class="p-2 bg-blue-600 rounded-lg">
 <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
 </svg>
 </div>
 <div>
 <h2 class="text-lg font-bold text-gray-900">رفع ملف الاستيراد</h2>
 <p class="text-sm text-gray-600">اختر ملف Excel أو CSV يحتوي على بيانات الطلاب</p>
 </div>
 </div>
 </div>

 <form action="{{ route('admin.import.students.process') }}" method="POST" enctype="multipart/form-data" class="p-6">
 @csrf

 {{-- Drag & Drop Zone --}}
 <div class="mb-6">
 <label for="file-upload" class="block">
 <div id="drop-zone" class="relative border-2 border-dashed border-gray-300 rounded-xl p-12 text-center hover:border-blue-400 hover:bg-blue-50 transition-all duration-200 cursor-pointer group">
 <input id="file-upload" name="file" type="file" accept=".xlsx,.xls,.csv" required class="sr-only" onchange="handleFileSelect(event)">

 <div id="upload-content">
 <div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4 group-hover:bg-blue-200 transition-colors">
 <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
 </svg>
 </div>
 <p class="text-lg font-semibold text-gray-900 mb-2">اسحب وأفلت الملف هنا</p>
 <p class="text-sm text-gray-500 mb-4">أو انقر للاختيار من الجهاز</p>
 <div class="flex items-center justify-center gap-2 text-xs text-gray-400">
 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
 </svg>
 <span>Excel (.xlsx, .xls) أو CSV (.csv)</span>
 <span>•</span>
 <span>حد أقصى 5 ميجابايت</span>
 </div>
 </div>

 <div id="file-info" class="hidden">
 <div class="flex items-center justify-center gap-3 p-4 bg-green-50 rounded-lg border border-green-200">
 <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
 </svg>
 <div class="flex-1 text-right">
 <p id="file-name" class="font-semibold text-gray-900"></p>
 <p id="file-size" class="text-sm text-gray-500"></p>
 </div>
 <button type="button" onclick="clearFile()" class="p-1 hover:bg-red-100 rounded-lg transition-colors">
 <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
 </svg>
 </button>
 </div>
 </div>
 </div>
 </label>

 @error('file')
 <p class="mt-2 text-sm text-red-600 flex items-center gap-2">
 <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
 <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
 </svg>
 {{ $message }}
 </p>
 @enderror
 </div>

 {{-- Action Buttons --}}
 <div class="flex items-center gap-3">
 <x-button type="submit" variant="primary" class="flex-1 justify-center">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
 </svg>
 بدء الاستيراد
 </x-button>
 <x-button type="button" variant="outline" onclick="clearFile()">
 مسح
 </x-button>
 </div>
 </form>
 </div>

 {{-- Column Reference --}}
 <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
 <div class="p-6 border-b border-gray-200 bg-gray-50">
 <div class="flex items-center gap-2">
 <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
 </svg>
 <h3 class="font-bold text-gray-900">الأعمدة المطلوبة في الملف</h3>
 </div>
 </div>
 <div class="overflow-x-auto">
 <table class="w-full">
 <thead class="bg-gray-50 border-b border-gray-200">
 <tr>
 <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">العمود</th>
 <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">مطلوب</th>
 <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">الوصف</th>
 </tr>
 </thead>
 <tbody class="divide-y divide-gray-200">
 <tr class="hover:bg-gray-50">
 <td class="px-4 py-3 text-sm">
 <code class="px-2 py-1 bg-gray-100 rounded text-blue-600 font-mono text-xs">name</code>
 </td>
 <td class="px-4 py-3">
 <span class="inline-flex items-center gap-1 text-xs font-medium text-green-700 bg-green-100 px-2 py-1 rounded">
 <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
 <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
 </svg>
 نعم
 </span>
 </td>
 <td class="px-4 py-3 text-sm text-gray-600">اسم الطالب</td>
 </tr>
 <tr class="hover:bg-gray-50">
 <td class="px-4 py-3 text-sm">
 <code class="px-2 py-1 bg-gray-100 rounded text-blue-600 font-mono text-xs">group</code>
 </td>
 <td class="px-4 py-3">
 <span class="inline-flex items-center gap-1 text-xs font-medium text-green-700 bg-green-100 px-2 py-1 rounded">
 <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
 <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
 </svg>
 نعم
 </span>
 </td>
 <td class="px-4 py-3 text-sm text-gray-600">رجال / نساء / أطفال</td>
 </tr>
 <tr class="hover:bg-gray-50">
 <td class="px-4 py-3 text-sm">
 <code class="px-2 py-1 bg-gray-100 rounded text-blue-600 font-mono text-xs">track</code>
 </td>
 <td class="px-4 py-3">
 <span class="inline-flex items-center gap-1 text-xs font-medium text-green-700 bg-green-100 px-2 py-1 rounded">
 <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
 <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
 </svg>
 نعم
 </span>
 </td>
 <td class="px-4 py-3 text-sm text-gray-600">حفظ / تأسيس</td>
 </tr>
 <tr class="hover:bg-gray-50">
 <td class="px-4 py-3 text-sm">
 <code class="px-2 py-1 bg-gray-100 rounded text-blue-600 font-mono text-xs">join_date</code>
 </td>
 <td class="px-4 py-3">
 <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded">اختياري</span>
 </td>
 <td class="px-4 py-3 text-sm text-gray-600">تاريخ الانضمام (2024-01-15)</td>
 </tr>
 <tr class="hover:bg-gray-50">
 <td class="px-4 py-3 text-sm">
 <code class="px-2 py-1 bg-gray-100 rounded text-blue-600 font-mono text-xs">parent_name</code>
 </td>
 <td class="px-4 py-3">
 <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded">اختياري</span>
 </td>
 <td class="px-4 py-3 text-sm text-gray-600">اسم ولي الأمر</td>
 </tr>
 <tr class="hover:bg-gray-50">
 <td class="px-4 py-3 text-sm">
 <code class="px-2 py-1 bg-gray-100 rounded text-blue-600 font-mono text-xs">parent_phone</code>
 </td>
 <td class="px-4 py-3">
 <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded">اختياري</span>
 </td>
 <td class="px-4 py-3 text-sm text-gray-600">رقم هاتف ولي الأمر</td>
 </tr>
 <tr class="hover:bg-gray-50">
 <td class="px-4 py-3 text-sm">
 <code class="px-2 py-1 bg-gray-100 rounded text-blue-600 font-mono text-xs">student_phone</code>
 </td>
 <td class="px-4 py-3">
 <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded">اختياري</span>
 </td>
 <td class="px-4 py-3 text-sm text-gray-600">رقم هاتف الطالب</td>
 </tr>
 <tr class="hover:bg-gray-50">
 <td class="px-4 py-3 text-sm">
 <code class="px-2 py-1 bg-gray-100 rounded text-blue-600 font-mono text-xs">class_name</code>
 </td>
 <td class="px-4 py-3">
 <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded">اختياري</span>
 </td>
 <td class="px-4 py-3 text-sm text-gray-600">اسم الحلقة</td>
 </tr>
 </tbody>
 </table>
 </div>
 </div>
 </div>

 {{-- Sidebar --}}
 <div class="space-y-6">
 {{-- Instructions Card --}}
 <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-xl shadow-sm overflow-hidden">
 <div class="p-6 border-b border-blue-200 bg-white/50">
 <div class="flex items-center gap-2">
 <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 <h3 class="font-bold text-gray-900">خطوات الاستيراد</h3>
 </div>
 </div>
 <div class="p-6">
 <ol class="space-y-4">
 <li class="flex gap-3">
 <div class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold">1</div>
 <p class="text-sm text-gray-700">حمّل النموذج أدناه</p>
 </li>
 <li class="flex gap-3">
 <div class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold">2</div>
 <p class="text-sm text-gray-700">املأ بيانات الطلاب في الملف</p>
 </li>
 <li class="flex gap-3">
 <div class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold">3</div>
 <p class="text-sm text-gray-700">احفظ بصيغة Excel أو CSV</p>
 </li>
 <li class="flex gap-3">
 <div class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold">4</div>
 <p class="text-sm text-gray-700">ارفع الملف وابدأ الاستيراد</p>
 </li>
 </ol>

 <div class="mt-6 pt-6 border-t border-blue-200">
 <x-button href="{{ route('admin.import.students.template') }}" variant="primary" class="w-full justify-center">
 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
 </svg>
 تحميل النموذج
 </x-button>
 </div>
 </div>
 </div>

 {{-- Tips Card --}}
 <div class="bg-amber-50 border border-amber-200 rounded-xl p-6">
 <div class="flex items-start gap-3">
 <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
 </svg>
 <div>
 <h4 class="font-semibold text-amber-900 mb-2">نصائح مهمة</h4>
 <ul class="space-y-2 text-sm text-amber-800">
 <li class="flex items-start gap-2">
 <span class="text-amber-600 mt-1">•</span>
 <span>تأكد من صحة تنسيق البيانات</span>
 </li>
 <li class="flex items-start gap-2">
 <span class="text-amber-600 mt-1">•</span>
 <span>لا تترك الحقول المطلوبة فارغة</span>
 </li>
 <li class="flex items-start gap-2">
 <span class="text-amber-600 mt-1">•</span>
 <span>استخدم التواريخ بصيغة YYYY-MM-DD</span>
 </li>
 </ul>
 </div>
 </div>
 </div>
 </div>
 </div>
</div>

<script>
// Drag and drop functionality
const dropZone = document.getElementById('drop-zone');
const fileInput = document.getElementById('file-upload');
const uploadContent = document.getElementById('upload-content');
const fileInfo = document.getElementById('file-info');
const fileName = document.getElementById('file-name');
const fileSize = document.getElementById('file-size');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
 dropZone.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
 e.preventDefault();
 e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
 dropZone.addEventListener(eventName, () => {
 dropZone.classList.add('border-blue-500', 'bg-blue-50');
 }, false);
});

['dragleave', 'drop'].forEach(eventName => {
 dropZone.addEventListener(eventName, () => {
 dropZone.classList.remove('border-blue-500', 'bg-blue-50');
 }, false);
});

dropZone.addEventListener('drop', (e) => {
 const files = e.dataTransfer.files;
 if (files.length) {
 fileInput.files = files;
 handleFileSelect({ target: fileInput });
 }
}, false);

function handleFileSelect(event) {
 const file = event.target.files[0];
 if (file) {
 fileName.textContent = file.name;
 fileSize.textContent = formatFileSize(file.size);
 uploadContent.classList.add('hidden');
 fileInfo.classList.remove('hidden');
 }
}

function clearFile() {
 fileInput.value = '';
 uploadContent.classList.remove('hidden');
 fileInfo.classList.add('hidden');
}

function formatFileSize(bytes) {
 if (bytes === 0) return '0 Bytes';
 const k = 1024;
 const sizes = ['Bytes', 'KB', 'MB'];
 const i = Math.floor(Math.log(bytes) / Math.log(k));
 return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}
</script>
@endsection
