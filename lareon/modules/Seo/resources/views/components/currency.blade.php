@props(['name' , 'value'=>[]])
@php
$value=(array)$value;
@endphp
<x-lareon::editor.input-select :value="$value" :inline="true" :required="true" :label="__('currency')" name="{{$name}}">
    @foreach(\Teksite\Extralaravel\Enums\Currencies::cases() as $lang)
        <option value="{{$lang->name}}">
            {{__($lang->value)}}
        </option>
    @endforeach
</x-lareon::editor.input-select>
