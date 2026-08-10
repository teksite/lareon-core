@props(['name','value'=>[], 'required'=>false  ])
@php
$finalName=$name."[article]"
@endphp
<fieldset class="fieldset">
    <legend class="legend">{{__('article')}}</legend>
    <div class="space-y-6">
        <x-lareon::editor.input :label="__('headline')" name="{{$finalName}}[headline]" :value="$value['headline'] ?? null" labelPosition="start" :required="$required" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('headline') ,'item'=>__('article')])"/>
        <x-lareon::editor.input-textarea :label="__('description')" name="{{$finalName}}[longitude]" labelPosition="start" :required="$required" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('meta')])">{{$value['description'] ?? null}}</x-lareon::editor.input-textarea>
        <x-seo::editor.partials.image :name="$finalName ?? []" :value="$value['image']?? []" />
        <x-seo::editor.partials.author :name="$finalName ?? []" :value="$value['author']?? []" />
        <x-seo::editor.partials.publisher :name="$finalName ?? []" :value="$value['publisher']?? []" />
    </div>
</fieldset>
