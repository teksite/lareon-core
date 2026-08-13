@props(['name','value'=>[], 'required'=>false , 'arrayName'=>'numberOfEmployees' ,'title'=>__('number of employees')])
@php
    $finalName = $name."[".$arrayName."]";
@endphp
<fieldset class="fieldset">
    <legend class="legend">{{$title}}</legend>
    <div class="space-y-6">
        <x-lareon::editor.input :label="__('value')" name="{{$finalName}}[numberOfEmployees]}[value]" :value="$value['value'] ?? null" labelPosition="top" dir="ltr" type="number" :required="$required"/>
    </div>
</fieldset>
