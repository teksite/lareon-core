@props(['name','value'=>[], 'required'=>false ,'arrayName'=>'product' ,'title'=>__('product')])
@php
        $finalName = $name."[".$arrayName."]";
@endphp
<fieldset class="fieldset">
    <legend class="legend">{{$title}}</legend>
    <div class="space-y-6">
        <x-lareon::editor.input :label="__('name')" name="{{$finalName}}[name]" :value="$value['name'] ?? null" labelPosition="top" :required="$required" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('name') ,'item'=>__('article')])"/>
        <x-lareon::editor.input-textarea :label="__('description')" name="{{$finalName}}[description]" labelPosition="top" :required="$required" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('meta')])">{{$value['description'] ?? null}}</x-lareon::editor.input-textarea>
        <x-lareon::editor.input dir="ltr" :label="__('image')" name="{{$finalName}}[image]" :value="$value['image'] ?? null" labelPosition="top" :required="$required" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('name') ,'item'=>__('main image')])"/>
    </div>
</fieldset>
