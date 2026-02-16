@props([
 'name' => '',
 'label' => '',
 'placeholder' => 'اختر...',
 'options' => [],
 'selected' => null,
 'required' => false,
 'disabled' => false,
 'error' => null,
 'helper' => null,
 'class' => '',
])

@php
  // RTL-aware select styling matching filter page pattern
  $baseSelectClasses = 'w-full pr-4 pl-12 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white appearance-none bg-no-repeat';

  // Custom select arrow icon (RTL - arrow on left for Arabic)
  $selectArrow = "url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22M6%208l4%204%204-4%22%2F%3E%3C%2Fsvg%3E')";

  $stateClasses = $error
  ? 'border-red-500 focus:border-red-500 focus:ring-red-500'
  : '';

  $selectClasses = collect([
  $baseSelectClasses,
  $stateClasses,
  'bg-[length:1.5rem]',
  'bg-[center_left_0.5rem]',
  $class,
  ])->filter()->implode(' ');
 @endphp

<div class="w-full">
 @if ($label)
 <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-2">
 {{ $label }}
 @if ($required)
 <span class="text-red-500" aria-label="مطلوب">*</span>
 @endif
 </label>
 @endif

 <div class="relative">
 <select
 id="{{ $name }}"
 name="{{ $name }}"
 @if($required) required aria-required="true" @endif
 @if($disabled) disabled @endif
 class="{{ $selectClasses }}"
 style="background-image: {{ $selectArrow }};"
 {{ $attributes }}
 >
 @if($placeholder && empty($slot->toHtml()))
 <option value="">{{ $placeholder }}</option>
 @endif
 @if(!empty($slot->toHtml()))
 {{-- Render slot content if provided --}}
 {{ $slot }}
 @else
 {{-- Otherwise render from options array --}}
 @foreach ($options as $value => $label)
 <option
 value="{{ $value }}"
 @if($selected !== null && $selected == $value) selected @endif
 >
 {{ $label }}
 </option>
 @endforeach
 @endif
  </select>
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
