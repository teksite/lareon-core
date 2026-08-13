@props(['name','value'=>[], 'required'=>true ,'arrayName'=>'geo' ,'title'=>__('geo')])
@php
    $finalName = $name."[".$arrayName."]";
@endphp
<fieldset class="fieldset">
    <legend class="legend">{{$title}}</legend>
    <div class="space-y-6">
        <x-lareon::editor.input :label="__('latitude')" name="{{$finalName}}[latitude]" :value="$value['latitude'] ?? null" labelPosition="top" dir="ltr" :required="$required"/>
        <x-lareon::editor.input :label="__('longitude')" name="{{$finalName}}[longitude]" :value="$value['longitude'] ?? null" labelPosition="top" dir="ltr" :required="$required"/>
    </div>
</fieldset>
