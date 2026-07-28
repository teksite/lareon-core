@props(['size' => 'xs','variant' => 'solid','color' => 'blue','disabled' => false,'fullWidth' => true,'rounded' => 'xl' ,'href' => null,'target' => '_self', 'content'=>null , 'can'=>[null]])
@php

    $convertedColor = match ($color){
      'index'=>  'teal',
      'create'=>  'green',
      'update'=>  'blue',
      'delete'=>  'red',
      'show'=>  'violet',
      default =>'gray'
    };

       $icon = match ($color){
      'index'=>  'list-number',
      'create'=>  'plus',

      default =>null
    };

@endphp
<x-lareon::links.simple class="min-w-fit w-fit flex gap-3 py-2 items-center" :size="$size" :variant="$variant" :color="$convertedColor" :disabled="$disabled" :fullWidth="$fullWidth" :rounded="$rounded" :href="$href" :target="$target" :can="$can">
    @if($icon)
        <x-tkicon icon="{{$icon}}" class="fill-none  text-slate-50 size-4 stroke-2"/>
    @endif
    {{$content ?? $slot}}
</x-lareon::links.simple>
