@props(['data'=>[] ,'name'=>'seo[website][data]'])
@php
    $websiteData=$data['website'] ?? [];
@endphp
<div class="space-y-6">
    <x-lareon::editor.input :required="true" :label="__('title')" name="{{$name}}[title]" :value="$websiteData['title'] ?? null"/>
    <x-lareon::editor.input-textarea :required="true" :label="__('description')" name="{{$name}}[description]">{{$websiteData['description'] ?? null}}</x-lareon::editor.input-textarea>
    <x-seo::lang :required="true" name="{{$name}}[language]" :value="$websiteData['language'] ?? null"/>
    <x-seo::currency :required="true" name="{{$name}}[currency]" :value="$websiteData['currency'] ?? null"/>
</div>
