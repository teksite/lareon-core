@props(['name','value'=>[], 'required'=>false  ])

<fieldset class="fieldset">
    <legend class="legend">{{__('number of employees')}}</legend>
    <div class="space-y-6">
        <x-lareon::editor.input :label="__('value')" name="{{$name}}[numberOfEmployees][value]" :value="$value['value'] ?? null" labelPosition="start" dir="ltr" type="number" :required="$required"/>
    </div>
</fieldset>
