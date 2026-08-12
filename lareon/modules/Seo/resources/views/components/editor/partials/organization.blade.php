@props(['name','value'=>[], 'required'=>true ,'arrayName'=>'organization' ,'title'=>__('organization')])
@php
    $finalName = $name."[".$arrayName."]";
    $dottedName = str_replace(['[', ']'], ['.', ''], $finalName);
@endphp
<fieldset class="fieldset">
    <legend class="legend">{{$title}}</legend>
    @error($dottedName)
    <p class="mb-4 message-error">{{ $message }}</p>
    @enderror
    <div class="space-y-6">
        <x-lareon::editor.input labelPosition="start" :label="__('title')" name="{{$finalName}}[organization][title]" :value="$value['organization']['title'] ?? null" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('website')])"/>
        <x-lareon::editor.input labelPosition="start" :label="__('logo')" name="{{$finalName}}[organization][logo]" :value="$value['organization']['logo'] ?? null" placeholder="https://exmaple.com//images/logo.jpg | /images/logo.jpg" dir="ltr" :required="true"/>
    </div>
</fieldset>
