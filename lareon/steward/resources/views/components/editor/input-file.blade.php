@props([
    'id' => null,
    'name' => '',
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
    'follow'=>null,
    'accept'=>[],
])

@php
    $dotName = str_replace(['[', ']'], ['.', ''], $name);
    $consideredValue= $old ? old($dotName , $value) : $value;
    $finalId = $id ?? $dotName;
    $hasError = $errors->has($dotName) || $error;
    $errorMessage = $error ?? ($errors->first($dotName) ?? null);

    $errorStyle = $hasError ? 'input-error' : '';
    $accepted=collect($accept ?? [])->map(fn ($e) => str_starts_with($e, '.') ? $e : ".$e")->implode(',');
@endphp

<div class="w-full {{ $wrapperClass }}">
    @if($label && $labelPosition === 'top')
        <x-lareon::inputs.label :title="$label" for="{{$finalId}}" class="mb-1" :markAsRequire="$required"/>
    @endif

    <div class="flex items-center gap-2">
        @if($label && $labelPosition === 'start')
            <x-lareon::inputs.label :title="$label" for="{{$finalId}}" class="w-fit min-w-fit" :markAsRequire="$required"/>
        @endif

        <x-lareon::inputs.file name="{{$name}}" id="{{$finalId}}" type="file" :value="$consideredValue" :disabled="$disabled" :required="$required" :readonly="$readonly" class="{{$inputClasses .' ' . $errorStyle}}" {{$attributes}} :accept="$accepted"/>

        @if($label && $labelPosition === 'end')
            <x-lareon::inputs.label :title="$label" for="{{$finalId}}" class="w-fit min-w-fit" :markAsRequire="$required"/>
        @endif

        @if(strlen(trim($follow ?? '')))
            <span class='w-fit min-w-fit'>{{$follow}}</span>
        @endif

    </div>


    @if($label && $labelPosition === 'bottom')
        <x-lareon::inputs.label :title="$label" for="{{$finalId}}" class="mt-1" :markAsRequire="$required"/>
    @endif
    <x-lareon::inputs.error :messages="$errorMessage ?? null"/>

</div>

