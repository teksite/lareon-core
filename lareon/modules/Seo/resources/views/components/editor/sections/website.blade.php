@props(['data'=>[] ,'name'=>'seo[website][data]'])
@php
    $websiteData=$data['website'] ?? [];
@endphp
<div class="space-y-6">
    <x-lareon::editor.input labelPosition="start" :required="true" :label="__('title')" name="{{$name}}[title]" :value="$websiteData['title'] ?? null" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('title'),'item'=>__('website') ])"/>
    <x-lareon::editor.input-textarea labelPosition="start" :required="true" :label="__('description')" name="{{$name}}[description]" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('title'),'item'=>__('website') ])">{{$websiteData['description'] ?? null}}</x-lareon::editor.input-textarea>
    <x-seo::lang labelPosition="start" :required="true" name="{{$name}}[language]" :value="$websiteData['language'] ?? null"/>
    <x-seo::currency labelPosition="start" :required="true" name="{{$name}}[currency]" :value="$websiteData['currency'] ?? null"/>
</div>
