@props(['name','value'=>[], 'required'=>false  ])

<fieldset class="fieldset">
    <legend class="legend">{{__('web page schema')}}</legend>
    <div class="space-y-6">
        <x-seo::editor.partials.web-page name="{{$name}}" :value="$value['webpage'] ?? []"/>
    </div>
</fieldset>
