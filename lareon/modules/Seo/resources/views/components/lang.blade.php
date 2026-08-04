@props(['name' , 'value'=>[]])
@php
$value=(array)$value;
@endphp
<x-lareon::editor.input-select :value="$value" :inline="true" :required="true" :label="__('language')" name="{{$name}}">
    @foreach(\Teksite\Extralaravel\Enums\Langs::cases() as $lang)
        <option value="{{$lang->name}}">
            {{__($lang->value)}}
        </option>
    @endforeach
</x-lareon::editor.input-select>
