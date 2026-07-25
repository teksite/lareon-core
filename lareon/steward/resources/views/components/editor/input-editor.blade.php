@props([
    'id' => null,
    'name' => '',
    'value' => null,
    'dir' => null,
    'old' => true,
    'inputClasses' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'label' => null,
    'labelPosition' => 'top', // top, bottom, start, end, none
    'error' => null,
    'wrapperClass' => null,
    'autocomplete' => 'off',
    'placeholder' => null,
])

@php
    $dotName = rtrim(
        str_replace(['[', ']'], ['.', ''], $name),
        '.'
    );

    $defaultValue = $value ?? trim((string) $slot);

    $consideredValue = $old
        ? old($dotName, $defaultValue)
        : $defaultValue;

    $finalId = $id ?? str_replace('.', '-', $dotName);

    $hasError = $errors->has($dotName) || filled($error);

    $errorMessage = $error ?: $errors->first($dotName);
@endphp

<div @class(['w-full', $wrapperClass])>

    @if($label && $labelPosition === 'top')
        <x-lareon::inputs.label :title="$label" :for="$finalId" class="mb-1" :markAsRequire="$required"/>
    @endif

    <div class="flex items-center gap-2">

        @if($label && $labelPosition === 'start')
            <x-lareon::inputs.label :title="$label" :for="$finalId" class="w-fit min-w-fit" :markAsRequire="$required"/>
        @endif

        <x-lareon::inputs.textarea :name="$name" :id="$finalId" :disabled="$disabled" :required="$required" :readonly="$readonly" :dir="$dir" :placeholder="$placeholder" :autocomplete="$autocomplete"
                @class([$inputClasses,'input-error' => $hasError, 'w-full block']) {{ $attributes }}
        >{{ $consideredValue }}</x-lareon::inputs.textarea>

        @if($label && $labelPosition === 'end')
            <x-lareon::inputs.label :title="$label" :for="$finalId" class="w-fit min-w-fit" :markAsRequire="$required"/>
        @endif
    </div>

    @if($label && $labelPosition === 'bottom')
        <x-lareon::inputs.label :title="$label" :for="$finalId" class="mt-1" :markAsRequire="$required"/>
    @endif
    <x-lareon::inputs.error :messages="$errorMessage"/>

</div>
