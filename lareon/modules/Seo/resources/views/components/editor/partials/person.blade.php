@props(['name','value'=>[], 'required'=>false ,'arrayName'=>'person' ,'title'=>__('person')])
@php
    $finalName = $name."[".$arrayName."]";
@endphp
<fieldset class="fieldset">
    <legend class="legend">{{$title}}</legend>
    <div class="space-y-6">
        <x-lareon::editor.input :label="__('name')" name="{{$finalName}}[name]" :value="$value['name'] ?? null" labelPosition="top" :required="$required" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('title')])"/>
        <x-lareon::editor.input :label="__('url')" name="{{$finalName}}[url]" :value="$value['url'] ?? null" labelPosition="top" dir="ltr" :required="$required" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('main image')])"/>
        <x-lareon::editor.input :label="__('image')" name="{{$finalName}}[image]" :value="$value['image'] ?? null" labelPosition="top" dir="ltr" :required="$required" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('current page')])"/>
        <x-lareon::editor.input :label="__('job title')" name="{{$finalName}}[jobTitle]" :value="$value['jobTitle'] ?? null" labelPosition="top" :required="$required" />
    </div>
</fieldset>
