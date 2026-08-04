@props(['data'=>[] ,'name'=>'seo[website][data]'])

<div class="space-y-6">
    <x-lareon::editor.input :required="true" :label="__('title')" name="{{$name}}[title]" :value="$data['title'] ?? null"/>
    <x-lareon::editor.input-textarea :required="true" :label="__('description')" name="{{$name}}[description]">{{$data['description'] ?? null}}</x-lareon::editor.input-textarea>
    <x-seo::lang :required="true" name="{{$name}}[language]" :value="$data['language'] ?? null"/>
    <x-seo::currency :required="true" name="{{$name}}[currency]" :value="$data['currency'] ?? null"/>
</div>
