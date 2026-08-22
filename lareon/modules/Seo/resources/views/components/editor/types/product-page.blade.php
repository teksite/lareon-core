@props(['name','value'=>[], 'required'=>false  ])
@php
    $finalName=$name."[article]";
@endphp
<fieldset class="fieldset space-y-6">
    <legend class="legend">{{__('article schema')}}</legend>
    <x-seo::editor.partials.article :name="$name" :value="$value['article']?? []"/>
    <x-seo::editor.partials.image :name="$name" :value="$value['image']?? []"/>
    <x-seo::editor.partials.author :name="$name" :value="$value['author']?? []"/>
    <x-seo::editor.partials.organization :name="$name.'[publisher]'" :title="__('publisher')" :value="$value['publisher']['organization']?? []"/>
</fieldset>
