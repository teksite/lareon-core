@props([
    'name',
    'options' => [],
    'disabled' => false,
    'label' => null,
    'wrapperClass' => null,
    'value'=>null,
    'required'=>false,
    'wrapperClass'=>null,
    'style_type'=>'inline',
    'old'=>true,

])

@php
    $dotName = str_replace(['[', ']'], ['.', ''], $name);

    $errorMessage = $errors->first($dotName);

    $errorClass = $errorMessage ? 'input-error' : '';

    $placeholderText = $placeholder ?? __('password');
    $inputWrapperClass = match (true){
      in_array($style_type ,['inline' , 'inline_start']) => 'flex items-center gap-2',
      default=>null
    };
    $consideredValue= $old ? old($dotName , $value) : $checked;

@endphp

<div class="{{ $wrapperClass }}">
    @if($label)
        <x-lareon::inputs.label :title="$label" class="mb-1" :markAsRequire="$required"/>
    @endif
    <ul class="{{$inputWrapperClass}}">
            @php
                $id=$dotName.'_'.$loop->index;
                $label = $option['label'] ?? $option[0] ?? '-';
                $val = $option['value'] ?? $option[1] ?? null;
                $disabled = $option['disabled'] ?? $option[2] ?? false;
            @endphp
            <li class="{{$inputWrapperClass}}">
                <x-lareon::inputs.label :title="$label" :for="$id"/>
                <x-lareon::inputs.checkbox id="{{$id}}" name="{{$name}}" :value="$value" :disabled="$disabled" :checked="$val === $consideredValue"/>
            </li>
        @endforeach
    </ul>

</div>
