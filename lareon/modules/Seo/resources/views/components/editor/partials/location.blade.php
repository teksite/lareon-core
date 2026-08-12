@props(['name','value'=>[], 'required'=>true, 'arrayName'=>'place' ,'title'=>__('location')])
@php
    $finalName = $name."[".$arrayName."]";
@endphp
<fieldset class="fieldset">
    <legend class="legend">{{$title}}</legend>
    <x-lareon::editor.input :label="__('name')" name="{{$finalName}}[name]" :value="$value['name'] ?? null" labelPosition="start" :required="$required" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('meta')])"/>

    <x-seo::editor.partials.address name="{{$name}}" :value="$value['address'] ?? []" :name="$finalName"/>
    <x-seo::editor.partials.virtual-location name="{{$name}}" :value="$value['VirtualLocation'] ?? []" :name="$finalName"/>

</fieldset>
