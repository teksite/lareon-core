@props(['name','value'=>[], 'required'=>false  ])
@php
    $finalName=$name."[article]";
@endphp
<fieldset class="fieldset space-y-6">
    <legend class="legend">{{__('article schema')}}</legend>
    <x-seo::editor.partials.product :name="$name" :value="$value['product']?? []"/>
</fieldset>
