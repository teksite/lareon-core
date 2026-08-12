@props(['name','value'=>[], 'required'=>false  ])

<fieldset class="fieldset">
    <legend class="legend">{{__('video object schema')}}</legend>
    <div class="space-y-6">
        <x-seo::editor.partials.video-object name="{{$name}}" :value="$value['VideoObject'] ?? []"/>
        <x-seo::editor.partials.image name="{{$name}}" :value="$value['thumbnailUrl'] ?? []" arrayName="thumbnailUrl" :title="__('thumbnails')"/>
        <x-seo::editor.partials.clips name="{{$name}}" :value="$value['clip'] ?? []" />
    </div>
</fieldset>
