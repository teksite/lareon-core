@props(['name','value'=>[], 'required'=>false ,'arrayName'=>'VideoObject' ,'title'=>__('video object')])
@php
    $finalName = $name."[".$arrayName."]";
@endphp
<fieldset class="fieldset">
    <legend class="legend">{{$title}}</legend>
    <div class="space-y-6">
        <x-lareon::editor.input :label="__('name')" name="{{$finalName}}[name]" :value="$value['name'] ?? null" labelPosition="top" :required="$required" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('meta')])"/>
        <x-lareon::editor.input-textarea :label="__('description')" name="{{$finalName}}[description]" labelPosition="top" :required="$required" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('meta')])">{{$value['description'] ?? null}}</x-lareon::editor.input-textarea>
        <div class="grid gap-6 md:grid-cols-2">
            <x-lareon::editor.input dir="ltr" :label="__('content url')" name="{{$finalName}}[contentUrl]" :value="$value['contentUrl'] ?? null" labelPosition="top" :required="$required" placeholder="https://example.com/videos/example.mp4 | /videos/example.mp4 "/>
            <x-lareon::editor.input dir="ltr" :label="__('embed url')" name="{{$finalName}}[embedUrl]" :value="$value['embedUrl'] ?? null" labelPosition="top" :required="$required" placeholder="https://example.com/videos/embed-url | /videos/embed-url "/>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <x-lareon::editor.input type="date" :label="__('upload date')" name="{{$finalName}}[uploadDate]" :value="$value['uploadDate'] ?? null" labelPosition="top" :required="$required" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('publish date')])"/>
            <x-lareon::editor.input type="number" min="0" :label="__('duration')" name="{{$finalName}}[duration]" :value="$value['duration'] ?? 0" labelPosition="top" :required="$required" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('duration') ,'item'=>__('video')])"/>
        </div>


    </div>
</fieldset>
