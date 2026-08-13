@props(['name','value'=>[], 'required'=>false ,'arrayName'=>'VirtualLocation' ,'title'=>__('virtual location')])
@php
    $finalName = $name."[".$arrayName."]";
@endphp
<fieldset class="fieldset">
    <legend class="legend">{{$title}}</legend>
    <x-lareon::editor.input dir="ltr" :label="__('url')" name="{{$finalName}}[url]" :value="$value['url'] ?? null" labelPosition="top" :required="$required" placeholder="https://example.com/stream/example | /stream/example "/>
</fieldset>
