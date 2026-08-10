@props(['name','value'=>[], 'required'=>false , 'arrayName'=>'numberOfEmployees'])
@php
    $finalName = $name."[".$arrayName."]";
@endphp
<fieldset class="fieldset">
    <legend class="legend">{{__('number of employees')}}</legend>
    <div class="space-y-6">
        <x-lareon::editor.input :label="__('value')" name="{{$finalName}}[numberOfEmployees]}[value]" :value="$value['value'] ?? null" labelPosition="start" dir="ltr" type="number" :required="$required"/>
    </div>
</fieldset>
