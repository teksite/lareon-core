@props([
    'name',
    'options' => [],
    'disabled' => false,
    'label' => null,
    'wrapperClass' => null,
    'value'=>null,
    'style_type'=>'inline',
    'old'=>true,
    'required'=>false,
    'default'=>null,
    'labelPosition'=>'top',
])

@php
    $dotName = str_replace(['[', ']'], ['.', ''], $name);
    $finalId = $id ?? $dotName;

    $errorMessage = $errors->first($dotName);

    $errorClass = $errorMessage ? 'input-error' : '';

    $placeholderText = $placeholder ?? __('password');
    $inputWrapperClass = match (true){
      in_array($style_type ,['inline' , 'inline_start']) => 'flex flex-wrap items-center gap-2',
      default=>null
    };
    $consideredValue= $old ? old($dotName , ($value ?? $default)) : ($value ?? $default);

@endphp

<div class="w-full {{ $wrapperClass }}">
    @if($label && $labelPosition === 'top')
        <x-lareon::inputs.label :title="$label" for="{{$finalId}}" class="mb-1" :markAsRequire="$required"/>
    @endif
    <div class="flex items-center gap-2">
        @if($label && $labelPosition === 'start')
            <x-lareon::inputs.label :title="$label" for="{{$finalId}}" class="w-fit min-w-fit" :markAsRequire="$required"/>
        @endif
        <ul class="{{$inputWrapperClass}}">
            @foreach($options as $option)
                @php
                    $id=$dotName.'_'.$loop->index.'_radio';
                    $label = $option['label'] ?? $option[0] ?? '-';
                    $val = $option['value'] ?? $option[1] ?? null;
                    $disabled = $option['disabled'] ?? $option[2] ?? false;
                @endphp
                <li class="{{$inputWrapperClass}}">
                    <x-lareon::inputs.label :title="$label" :for="$id"/>
                    <x-lareon::inputs.radio id="{{$id}}" name="{{$name}}" value="{{$val}}" :disabled="$disabled" :checked="$val == $consideredValue"/>
                </li>
            @endforeach
        </ul>
        @if($label && $labelPosition === 'end')
            <x-lareon::inputs.label :title="$label" for="{{$finalId}}" class="w-fit min-w-fit" :markAsRequire="$required"/>
        @endif
    </div>

    <x-lareon::inputs.error :messages="$errorMessage ?? null"/>

</div>
