@props(['name','value'=>[], 'required'=>true, 'arrayName'=>'location' ,'title'=>__('location')])
@php
    $finalName = $name."[".$arrayName."]";
@endphp
<fieldset class="fieldset">
    <legend class="legend">{{$title}}</legend>
    <x-lareon::editor.input :label="__('name')" name="{{$finalName}}[place][name]" :value="$value['place']['name'] ?? null" labelPosition="top" :required="$required" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('meta')])"/>

    <x-seo::editor.partials.address name="{{$name}}" :value="$value['place']['address'] ?? []" :name="$finalName.'[place]'"/>
    <x-seo::editor.partials.virtual-location name="{{$name}}" :value="$value['VirtualLocation'] ?? []" :name="$finalName"/>

</fieldset>
