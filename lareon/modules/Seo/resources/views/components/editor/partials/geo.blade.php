@props(['name','value'=>[], 'required'=>true  ])

<fieldset class="fieldset">
    <legend class="legend">{{__('geo')}}</legend>
    <div class="space-y-6">
        <x-lareon::editor.input :label="__('latitude')" name="{{$name}}[location][latitude]" :value="$value['latitude'] ?? null" labelPosition="start" dir="ltr" :required="$required"/>
        <x-lareon::editor.input :label="__('longitude')" name="{{$name}}[location][longitude]" :value="$value['longitude'] ?? null" labelPosition="start" dir="ltr" :required="$required"/>
    </div>
</fieldset>
