@props(['value'=>[] ,'name'=>'seo'])
@php
    $finalName = $name."[website]";
@endphp
<div class="space-y-6">
    <x-lareon::editor.input labelPosition="start" :required="true" :label="__('title')" name="{{$finalName}}[title]" :value="$value['title'] ?? null" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('title'),'item'=>__('website') ])"/>
    <x-lareon::editor.input-textarea labelPosition="start" :required="true" :label="__('description')" name="{{$finalName}}[description]" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('title'),'item'=>__('website') ])">{{$value['description'] ?? null}}</x-lareon::editor.input-textarea>
    <x-seo::lang labelPosition="start" :required="true" name="{{$finalName}}[language]" :value="$value['language'] ?? null"/>
    <x-seo::currency labelPosition="start" :required="true" name="{{$finalName}}[currency]" :value="$value['currency'] ?? null"/>
</div>
