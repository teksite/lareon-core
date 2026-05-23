@props(['selected'=>false, "disabled"=>false ,'required'=>false ,'multiple'=>false, $options =>[] ,'placeholder'=>null])
@php
    $selectedValues = is_array($selected) ? $selected : (array)$selected;

    $classes = 'input';
    if ($multiple) {
        $classes .= ' multiple-select';
    }
@endphp

<select @required($required) {{$disabled ? 'disabled':''}} {{$multiple ? 'multiple' : ''}} {{$attributes->merge(['class'=>$classes])}} >
    @if($placeholder && !$multiple)
        <option value="">{{ $placeholder }}</option>
    @endif
    @if(isset($slot) && !$slot->isEmpty())
        {{ $slot }}
    @else
        @foreach($options as $option )
            @php
                if (is_array($option)) {
                    $value = array_key_first($option);
                   $label = reset($option);
                } else {
                    $value = $option;
                    $label = $option;
                }
            @endphp
            <option value="{{ $value }}" @selected(in_array($value, $selectedValues)) >{{ $label }}</option>
        @endforeach
    @endif
</select>
