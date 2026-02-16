@props([
 'name' => '',
 'label' => '',
 'type' => 'text',
 'placeholder' => '',
 'value' => null,
 'required' => false,
 'disabled' => false,
 'error' => null,
 'helper' => null,
 'icon' => null,
 'class' => '',
])

@php
 $baseInputClasses = 'w-full px-4 py-3 bg-white border-2 rounded-lg text-gray-900 text-sm transition-colors focus:outline-none disabled:bg-gray-100 disabled:cursor-not-allowed placeholder:text-gray-400';

 $stateClasses = $error
 ? 'border-red-500 focus:border-red-500 focus:ring-2 focus:ring-red-200'
 : 'border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200';

 $inputClasses = collect([
 $baseInputClasses,
 $stateClasses,
 $icon ? 'pr-11' : '',
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

 <div class="relative">
 <input
 type="{{ $type }}"
 id="{{ $name }}"
 name="{{ $name }}"
 @if($value !== null) value="{{ $value }}" @endif
 placeholder="{{ $placeholder }}"
 @if($required) required aria-required="true" @endif
 @if($disabled) disabled @endif
 class="{{ $inputClasses }}"
 {{ $attributes }}
 >

 @if ($icon)
 <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
 {{ $icon }}
 </div>
 @endif
 </div>

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
