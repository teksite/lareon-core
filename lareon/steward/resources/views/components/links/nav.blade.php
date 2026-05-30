@props(['size' => 'xs','variant' => 'solid','color' => 'blue','disabled' => false,'fullWidth' => true,'rounded' => 'xl' ,'href' => null,'target' => '_self', 'content'=>null , 'can'=>[null]])


@php

    $convertedColor = match ($color){
      'index'=>  'teal',
      'create'=>  'green',
      'update'=>  'blue',
      'delete'=>  'red',
      default =>'gray'
    };

@endphp
<x-lareon::links.simple class="min-w-fit w-fit" :size="$size" :variant="$variant" :color="$convertedColor" :disabled="$disabled" :fullWidth="$fullWidth" :rounded="$rounded" :href="$href" :target="$target" :can="$can">
    {{$content ?? $slot}}
</x-lareon::links.simple>
