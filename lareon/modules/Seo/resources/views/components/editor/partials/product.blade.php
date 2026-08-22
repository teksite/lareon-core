@props(['name','value'=>[], 'required'=>false ,'arrayName'=>'product' ,'title'=>__('product')])
@php
    $finalName = $name."[".$arrayName."]";
@endphp
<fieldset class="fieldset">
    <legend class="legend">{{$title}}</legend>
    <div class="space-y-6">
        <div class="grid gap-6 md:grid-cols-2">
            <x-lareon::editor.input :label="__('name')" name="{{$finalName}}[name]" :value="$value['name'] ?? null" labelPosition="top" :required="$required" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('meta')])"/>
            <x-lareon::editor.input dir="ltr" :label="__('image')" name="{{$finalName}}[image]" :value="$value['image'] ?? null" labelPosition="top" :required="$required" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('main image')])"/>
        </div>
        <x-lareon::editor.input-textarea :label="__('description')" name="{{$finalName}}[description]" labelPosition="top" :required="$required" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('meta')])">{{$value['description'] ?? null}}</x-lareon::editor.input-textarea>

        <div class="grid gap-6 md:grid-cols-3">
            <x-lareon::editor.input :label="__('gtin8')" name="{{$finalName}}[gtin8]" :value="$value['gtin8'] ?? null" labelPosition="top" :required="$required" />
            <x-lareon::editor.input :label="__('gtin13')" name="{{$finalName}}[gtin13]" :value="$value['gtin13'] ?? null" labelPosition="top" :required="$required" />
            <x-lareon::editor.input :label="__('gtin14')" name="{{$finalName}}[gtin14]" :value="$value['gtin14'] ?? null" labelPosition="top" :required="$required" />
            <x-lareon::editor.input :label="__('mpn')" name="{{$finalName}}[mpn]" :value="$value['mpn'] ?? null" labelPosition="top" :required="$required" />
            <x-lareon::editor.input :label="__('sku')" name="{{$finalName}}[sku]" :value="$value['sku'] ?? null" labelPosition="top" :required="$required" />
        </div>
    </div>
</fieldset>
