@props(['name','value'=>[], 'required'=>false , 'arrayName'=>'identifier' ,'title'=>__('identifier')])
@php
    $finalName = $name."[".$arrayName."]";
@endphp
<fieldset class="fieldset">
    <legend class="legend">{{$title}}</legend>
        <div class="grid gap-6 md:grid-cols-2">
            <x-lareon::editor.input :label="__('name')" name="{{$finalName}}[name]" :value="$value['name'] ?? null" labelPosition="top" :required="$required" />
            <x-lareon::editor.input :label="__('value')" name="{{$finalName}}[value]" :value="$value['value'] ?? null" labelPosition="top" :required="$required" />
        </div>
</fieldset>
