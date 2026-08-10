@props(['name','value'=>[], 'required'=>false  ])

<fieldset class="fieldset">
    <legend class="legend">{{__('web page')}}</legend>
    <div class="space-y-6">
        <x-lareon::editor.input :label="__('headline')" name="{{$name}}[webPage][headline]" :value="$value['headline'] ?? null" labelPosition="start" :required="$required" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('headline') ,'item'=>__('article')])"/>
        <x-lareon::editor.input-textarea :label="__('description')" name="{{$name}}[webPage][longitude]" labelPosition="start" :required="$required" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('meta')])">{{$value['description'] ?? null}}</x-lareon::editor.input-textarea>
        <x-lareon::editor.input :label="__('image')" dir="ltr" name="{{$name}}[webPage][image]" :value="$value['image'] ?? null" labelPosition="start" :required="$required" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('main image')])"/>
    </div>
</fieldset>
