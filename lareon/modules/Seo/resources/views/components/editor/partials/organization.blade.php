@props(['name'=>'seo[schema]','value'=>[], 'required'=>false, 'arrayName'=>'organization' ,'title'=>__('organization')])
@php
    $finalName = $name."[".$arrayName."]";
@endphp
<fieldset class="fieldset">
    <legend class="legend">{{$title}}</legend>
    <div class="grid gap-6 md:grid-cols-2">
        <x-lareon::editor.input :label="__('name')" name="{{$finalName}}[name]" :value="$value['name'] ?? null" labelPosition="top" :required="$required"/>
        <x-lareon::editor.input :label="__('logo')" name="{{$finalName}}[logo]" :value="$value['logo'] ?? null" labelPosition="top" dir="ltr" :required="$required" placeholder="https://example.com/logo.png | /logo.png "/>
    </div>
</fieldset>
