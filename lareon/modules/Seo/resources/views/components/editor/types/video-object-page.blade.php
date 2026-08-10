@props(['name','value'=>[], 'required'=>false  ])

<fieldset class="fieldset">
    <legend class="legend">{{__('video object page')}}</legend>
    <div class="space-y-6">
        <x-seo::editor.partials.video-object name="{{$name}}" :value="$value['VideoObject'] ?? []"/>
    </div>
</fieldset>
