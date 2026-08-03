@props(['size' => 'xs','variant' => 'solid','color' => 'blue','disabled' => false,'fullWidth' => true,'rounded' => 'xl' ,'href' => null,'target' => '_self', 'content'=>null , 'can'=>[null]])
@php

    $convertedColor = match ($color){
      'index'=>  'teal',
      'create'=>  'green',
      'update'=>  'blue',
      'delete','trash'=>  'red',
      'show'=>  'violet',
      default =>'gray'
    };

    $icon = match ($color){
      'index'=>  'list-number',
      'create'=>  'plus',
      'trash'=>  'trash-opened',
      default =>null
    };
    $variantType = match ($color){
      'trash'=>  'outline',
      default =>'solid'
    };

@endphp
<x-lareon::links.simple class="min-w-fit w-fit flex gap-3 py-2 items-center" :size="$size" :variant="$variantType" :color="$convertedColor" :disabled="$disabled" :fullWidth="$fullWidth" :rounded="$rounded" :href="$href" :target="$target" :can="$can">
    @if($icon)
        <x-tkicon icon="{{$icon}}" class="fill-none size-4 stroke-2 {{$variantType==='outline' ? 'text-red-600' : 'text-slate-50'}}"/>
    @endif
    {{$content ?? $slot}}
</x-lareon::links.simple>
