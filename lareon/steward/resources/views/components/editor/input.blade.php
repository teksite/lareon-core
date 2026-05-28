@props([
    'id' => null,
    'name' => '',
    'type' => 'text',
    'value' => null,
    'dir' => null,
    'old'=>true,
    'inputClasses'=>'',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'label' => null,
    'labelPosition' => 'top', // top, bottom, start, end, none
    'error' => null,
    'wrapperClass' => null,
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
        <x-lareon::inputs.label :title="$label" for="{{$finalId}}" class="mb-1" :requried="$required"/>
    @endif

    <div class="flex items-center gap-2">
        @if($label && $labelPosition === 'start')
            <x-lareon::inputs.label :title="$label" for="{{$finalId}}" class="w-fit"/>
        @endif
        <x-lareon::inputs.text name="{{$name}}" id="{{$finalId}}" :type="$type" :value="$consideredValue" :disabled="$disabled" :required="$required" :readonly="$readonly" class="{{$inputClasses .' ' . $errorStyle}}"/>
        @if($label && $labelPosition === 'end')
            <x-lareon::inputs.label :title="$label" for="{{$finalId}}" class="w-fit"/>
        @endif
    </div>
    <x-lareon::inputs.error :messages="$errorMessage ?? null"/>

    @if($label && $labelPosition === 'bottom')
        <x-lareon::inputs.label :title="$label" for="{{$finalId}}" class="mt-1"/>
    @endif
</div>
