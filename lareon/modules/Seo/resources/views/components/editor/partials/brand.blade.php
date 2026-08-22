@props(['name','value'=>[], 'required'=>false ,'arrayName'=>'brand' ,'title'=>__('brand')])
@php
        $finalName = $name."[".$arrayName."]";
@endphp
<fieldset class="fieldset">
    <legend class="legend">{{$title}}</legend>
    <div class="space-y-6">
        <x-lareon::editor.input :label="__('name')" name="{{$finalName}}[name]" :value="$value['name'] ?? null" labelPosition="top" :required="$required" />
    </div>
</fieldset>
