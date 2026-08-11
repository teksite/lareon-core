@props(['name','value'=>[], 'required'=>false  ])

<fieldset class="fieldset">
    <legend class="legend">{{__('event page schema')}}</legend>
    <div class="space-y-6">
        <x-seo::editor.partials.event name="{{$name}}" :value="$value['event'] ?? []"/>
        <x-seo::editor.partials.location name="{{$name}}" :value="$value['place'] ?? []"/>
        <x-seo::editor.partials.performer name="{{$name}}" :value="$value['performer'] ?? []"/>
        <x-seo::editor.partials.offers name="{{$name}}" :value="$value['offers'] ?? []"/>
    </div>
</fieldset>
