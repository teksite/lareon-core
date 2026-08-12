@props(['name','value'=>[], 'required'=>false ,'arrayName'=>'article' ,'title'=>__('article')])
@php
        $finalName = $name."[".$arrayName."]";
@endphp
<fieldset class="fieldset">
    <legend class="legend">{{$title}}</legend>
    <div class="space-y-6">
        <x-lareon::editor.input-select labelPosition="start" :label="__('type')" name="{{$finalName}}[type]" :value="$value['type'] ?? null" :required="$required">
            @foreach(\Lareon\Modules\Seo\App\Schema\SchemaOption::get('article_type') as $key=>$desc)
                <option value="{{$key}}">{{__($key)}}: {{__($desc)}}</option>
            @endforeach
        </x-lareon::editor.input-select>
        <x-lareon::editor.input :label="__('headline')" name="{{$finalName}}[headline]" :value="$value['headline'] ?? null" labelPosition="start" :required="$required" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('headline') ,'item'=>__('article')])"/>
        <x-lareon::editor.input-textarea :label="__('description')" name="{{$finalName}}[description]" labelPosition="start" :required="$required" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('meta')])">{{$value['description'] ?? null}}</x-lareon::editor.input-textarea>
    </div>
</fieldset>
