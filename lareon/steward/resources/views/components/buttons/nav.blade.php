@props(['type' => 'submit', 'size' => 'md', 'variant' => 'solid', 'color' => 'blue', 'disabled' => false, 'fullWidth' => true, 'rounded' => 'xl',  'content'=>null ,  'can'=>[null]])

@php
    $convertedColor = match ($color){
      'index'=>  'teal',
      'create'=>  'green',
      'update'=>  'blue',
      'delete'=>  'red',
      'enable'=>  'cyan',
      default =>'gray'
    };

@endphp
<x-lareon::buttons.simple :type="$type" :size="$size" :variant="$variant" :color="$convertedColor" :disabled="$disabled" :fullWidth="$fullWidth" :rounded="$rounded"  :can="$can">
    {{$content ?? $slot}}
</x-lareon::buttons.simple>
