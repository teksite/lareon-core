@props(['name','value'=>[], 'required'=>false , 'arrayName'=>'web page'])
@php
    $finalName = $name."[".$arrayName."]";
@endphp
<fieldset class="fieldset">
    <legend class="legend">{{$title}}</legend>
    <div class="space-y-6">
        <x-lareon::editor.input :label="__('title')" name="{{$finalName}}[title]" :value="$value['title'] ?? null" labelPosition="start" :required="$required" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('meta')])"/>
        <x-lareon::editor.input-textarea :label="__('description')" name="{{$finalName}}[description]" labelPosition="start" :required="$required" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('meta')])">{{$value['description'] ?? null}}</x-lareon::editor.input-textarea>
        <x-lareon::editor.input :label="__('image')" dir="ltr" name="{{$finalName}}[image]" :value="$value['image'] ?? null" labelPosition="start" :required="$required" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('main image')])"/>
    </div>
</fieldset>
