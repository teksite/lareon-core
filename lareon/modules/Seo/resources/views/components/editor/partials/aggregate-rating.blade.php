@props(['name','value'=>[], 'required'=>true ,'arrayName'=>'aggregateRating'  ])
@php
    $finalName = $name."[".$arrayName."]";
@endphp
<fieldset class="fieldset">
    <legend class="legend">{{__('aggregate rating')}}</legend>
    <div class="space-y-6">
        <x-lareon::editor.input type="number" min="0" max="5" step="0.1" :label="__('value')" name="{{$finalName}}[ratingValue]" :value="$value['ratingValue'] ?? null" labelPosition="start" dir="ltr" :required="false"/>
        <x-lareon::editor.input type="number" min="0" step="1" :label="__('count')" name="{{$finalName}}[ratingCount]" :value="$value['ratingCount'] ?? null" labelPosition="start" dir="ltr" :required="false"/>
        <x-lareon::editor.input type="number" min="0" max="5" step="0.1" :label="__('best rating')" name="{{$finalName}}[bestRating]" :value="$value['bestRating'] ?? null" labelPosition="start" dir="ltr" :required="false"/>
        <x-lareon::editor.input type="number" min="0" max="5" step="0.1" :label="__('best rating')" name="{{$finalName}}[worstRating]" :value="$value['worstRating'] ?? null" labelPosition="start" dir="ltr" :required="false"/>
    </div>
</fieldset>
