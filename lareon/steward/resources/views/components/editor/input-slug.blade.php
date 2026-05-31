@props([
    'id' => null,
    'name' => 'slug',
    'value' => null,
    'old'=>true,
    'inputClasses'=>'',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'label' => null,
    'labelPosition' => 'top', // top, bottom, start, end, none
    'error' => null,
    'wrapperClass' => null,
   'autocomplete'=>'false',
   'placeholder'=>null,
   'href'=>null,
   'showSiteUrl'=>true,
   'showUrl'=>true,
])

@php
    $dotName = str_replace(['[', ']'], ['.', ''], $name);
    $consideredValue= $old ? old($dotName , $value) : $value;
    $finalId = $id ?? $dotName;
    $hasError = $errors->has($dotName) || $error;
    $errorMessage = $error ?? ($errors->first($dotName) ?? null);

    $errorStyle = $hasError ? 'input-error' : '';
@endphp

<div class="w-full {{ $wrapperClass }}">
    @if($label && $labelPosition === 'top')
        <x-lareon::inputs.label :title="$label" for="{{$finalId}}" class="mb-1" :markAsRequire="$required"/>
    @endif


    <div class="flex items-center gap-2">
        @if($label && $labelPosition === 'start')
            <x-lareon::inputs.label :title="$label" for="{{$finalId}}" class="w-fit min-w-fit" :markAsRequire="$required"/>
        @endif

        <div class="flex items-center gap-2 w-full"  dir="ltr">
            @if($showSiteUrl)
                <span class="text-xs font-bold text-gray-600">{{url('/')}}/</span>
            @endif
            <x-lareon::inputs.text name="{{$name}}" id="{{$finalId}}" type="text" :value="$consideredValue" :disabled="$disabled" :required="$required" :readonly="$readonly" class="{{$inputClasses .' ' . $errorStyle}}" dir="ltr" autocomplete="{{$autocomplete}}" placeholder="{{$placeholder}}"/>
            @if($value  && $showUrl)
                <x-lareon::links.action type="show" :href="$value"/>
            @endif
        </div>
        @if($label && $labelPosition === 'end')
            <x-lareon::inputs.label :title="$label" for="{{$finalId}}" class="w-fit min-w-fit" :markAsRequire="$required"/>
        @endif
    </div>


    @if($label && $labelPosition === 'bottom')
        <x-lareon::inputs.label :title="$label" for="{{$finalId}}" class="mt-1" :markAsRequire="$required"/>
    @endif

    <x-lareon::inputs.error :messages="$errorMessage ?? null"/>

</div>
