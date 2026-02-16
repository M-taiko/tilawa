@props([
 'name' => '',
 'label' => '',
 'placeholder' => '',
 'value' => null,
 'rows' => 4,
 'required' => false,
 'disabled' => false,
 'error' => null,
 'helper' => null,
 'class' => '',
])

@php
 $baseTextareaClasses = 'w-full px-4 py-3 bg-white border-2 rounded-lg text-gray-900 text-sm transition-colors focus:outline-none disabled:bg-gray-100 disabled:cursor-not-allowed placeholder:text-gray-400 resize-y min-h-[100px]';

 $stateClasses = $error
 ? 'border-red-500 focus:border-red-500 focus:ring-2 focus:ring-red-200'
 : 'border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200';

 $textareaClasses = collect([
 $baseTextareaClasses,
 $stateClasses,
 $class,
 ])->filter()->implode(' ');
@endphp

<div class="w-full">
 @if ($label)
 <label for="{{ $name }}" class="block text-sm font-semibold text-gray-700 mb-2">
 {{ $label }}
 @if ($required)
 <span class="text-red-500" aria-label="مطلوب">*</span>
 @endif
 </label>
 @endif

 <textarea
 id="{{ $name }}"
 name="{{ $name }}"
 rows="{{ $rows }}"
 placeholder="{{ $placeholder }}"
 @if($required) required aria-required="true" @endif
 @if($disabled) disabled @endif
 class="{{ $textareaClasses }}"
 {{ $attributes }}
 >{{ $value ?? old($name) ?? '' }}</textarea>

 @if ($error)
 <p class="text-xs text-red-600 mt-1.5 flex items-center gap-1" role="alert">
 <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
 </svg>
 {{ $error }}
 </p>
 @elseif ($helper)
 <p class="text-xs text-gray-500 mt-1.5">{{ $helper }}</p>
 @endif
</div>
