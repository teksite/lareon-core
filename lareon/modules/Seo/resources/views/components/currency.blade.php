@props(['name' , 'value'=>[]])
@php
$value=(array)$value;
$dotName = str_replace(['[', ']'], ['.', ''], $name);

@endphp
<x-lareon::editor.input-select :value="$value" :inline="true" :required="true" :label="__('currency')" name="{{$name}}" :value="$value">
    @foreach(\Lareon\Modules\Seo\App\Enums\Currencies::cases() as $currency)
        <option value="{{$currency->name}}">
            {{__($currency->value)}}
        </option>
    @endforeach
</x-lareon::editor.input-select>
