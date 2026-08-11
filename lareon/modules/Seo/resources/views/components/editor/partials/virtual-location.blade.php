@props(['name','value'=>[], 'required'=>false ,'arrayName'=>'VirtualLocation'])
@php
    $finalName = $name."[".$arrayName."]";
@endphp
<fieldset class="fieldset">
    <legend class="legend">{{__('virtual location')}}</legend>
    <x-lareon::editor.input dir="ltr" :label="__('url')" name="{{$finalName}}[url]" :value="$value['url'] ?? null" labelPosition="start" :required="$required" placeholder="https://example.com/stream/example | /stream/example "/>
</fieldset>
