@props(['name','value'=>[], 'required'=>true ,'arrayName'=>'geo'  ])
@php
    $finalName = $name."[".$arrayName."]";
@endphp
<fieldset class="fieldset">
    <legend class="legend">{{__('geo')}}</legend>
    <div class="space-y-6">
        <x-lareon::editor.input :label="__('latitude')" name="{{$finalName}}[latitude]" :value="$value['latitude'] ?? null" labelPosition="start" dir="ltr" :required="$required"/>
        <x-lareon::editor.input :label="__('longitude')" name="{{$finalName}}[longitude]" :value="$value['longitude'] ?? null" labelPosition="start" dir="ltr" :required="$required"/>
    </div>
</fieldset>
