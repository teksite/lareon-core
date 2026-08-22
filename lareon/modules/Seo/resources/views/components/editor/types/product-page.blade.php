@props(['name','value'=>[], 'required'=>false  ])
@php
    $finalName=$name."[product]";
@endphp
<fieldset class="fieldset space-y-6">
    <legend class="legend">{{__('product schema')}}</legend>
    <x-seo::editor.partials.product :name="$name" :value="$value['product']?? []"/>
    <x-seo::editor.partials.brand :name="$name" :value="$value['brand']?? []"/>
</fieldset>
