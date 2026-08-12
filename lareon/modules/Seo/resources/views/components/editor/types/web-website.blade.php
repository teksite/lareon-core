@props(['value'=>[] ,'name'=>'seo', 'arrayName'=>'website'])
@php
    $finalName = $name."[".$arrayName."]";
@endphp
<div class="space-y-6">
    <x-lareon::editor.input labelPosition="start" :required="true" :label="__('title')" name="{{$finalName}}[title]" :value="$value['title'] ?? null" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('title'),'item'=>__('website') ])"/>
    <x-lareon::editor.input labelPosition="start" :required="true" :label="__('alternate name')" name="{{$finalName}}[alternateName]" :value="$value['alternateName'] ?? null" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('alternateName'),'item'=>__('website') ])"/>
    <x-lareon::editor.input-textarea labelPosition="start" :required="true" :label="__('description')" name="{{$finalName}}[description]" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('title'),'item'=>__('website') ])">{{$value['description'] ?? null}}</x-lareon::editor.input-textarea>
    <x-seo::lang labelPosition="start" :required="true" name="{{$finalName}}[inLanguage]" :value="$value['inLanguage'] ?? null" :inline="true" :multiple="true"/>
    <x-seo::currency labelPosition="start" :required="true" name="{{$finalName}}[currency]" :value="$value['currency'] ?? null"/>
</div>
