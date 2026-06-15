@props([
    'name',
    'label' => null,
    'options' => [],
    'value' => null,
    'required' => false,
    'disabled' => false,
    'placeholder' => null,
    'multiple' => false,
    'old' => true,
    'wrapperClass' => null,
])

@php
    $dotName = str_replace(['[', ']'], ['.', ''], $name);
    $errorMessage = $errors->first($dotName);
    $errorClass = $errorMessage ? 'input-error' : '';
    $selected = $old ? old($dotName, $value) : $value;

    $selectedValues = is_array($selected) ? $selected : [$selected];

@endphp

<div class="{{ $wrapperClass }}">
    @if($label)
        <x-lareon::inputs.label :title="$label" :markAsRequire="$required" class="mb-1"/>
    @endif
    <x-lareon::inputs.select {{$attributes}} :name="$name" :required="$required" :disabled="$disabled" :multiple="$multiple" :placeholder="$placeholder" :options="$options" :selected="$selectedValues" class="{{ $errorClass }}">
        {{$slot}}
    </x-lareon::inputs.select>
  <div>
      <x-lareon::inputs.error :messages="$errorMessage"/>
  </div>

</div>
