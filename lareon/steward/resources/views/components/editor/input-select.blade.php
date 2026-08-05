@props([
    'name',
    'label' => null,
    'value' => null,
    'required' => false,
    'disabled' => false,
    'placeholder' => null,
    'multiple' => false,
    'old' => true,
    'wrapperClass' => null,
    'inline' => false,
    'labelPosition' => 'top', // top, bottom, start, end, none
    'follow'=>null,
])

@php
    $dotName = str_replace(['[', ']'], ['.', ''], $name);
    $errorMessage = $errors->first($dotName);
    $errorClass = $errorMessage ? 'input-error' : '';
    $selected = $old ? old($dotName, $value) : $value;
    $finalId = $id ?? $dotName;

    $selectedValues = is_array($selected) ? $selected : [$selected];

@endphp

<div class="{{ $wrapperClass }}">
    @if($label && $labelPosition === 'top')
        <x-lareon::inputs.label :title="$label" for="{{$finalId}}" class="mb-1" :markAsRequire="$required"/>
    @endif

    <div class="flex items-center gap-2">
        @if($label && $labelPosition === 'start')
            <x-lareon::inputs.label :title="$label" for="{{$finalId}}" class="w-fit min-w-fit" :markAsRequire="$required"/>
        @endif
        <x-lareon::inputs.select {{$attributes}} :name="$name" id="{{$finalId}}" :required="$required" :disabled="$disabled" :multiple="$multiple" :placeholder="$placeholder" :selected="$selectedValues" class="{{ $errorClass }}" :inline="$inline">
            {{$slot}}
        </x-lareon::inputs.select>

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

