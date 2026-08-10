@props(['name'=>'seo[schema]','value'=>[], 'required'=>false  ])
@php
    $finalName = $name . '[author]';
@endphp
<fieldset class="fieldset space-y-6">
    <legend class="legend">{{__('author')}}</legend>
    <div class="grid gap-6 md:grid-cols-3">

        <x-lareon::editor.input-select labelPosition="start" :label="__('type')" name="{{$finalName}}[type]" :value="$value['type'] ?? null" :required="$required">
            @foreach(\Lareon\Modules\Seo\App\Schema\SchemaOption::get('author_list') as $key=>$desc)
                <option value="{{$key}}">{{$desc}}</option>
            @endforeach
        </x-lareon::editor.input-select>

        <x-lareon::editor.input :label="__('name')" name="{{$finalName}}[name]" :value="$value['name'] ?? null" labelPosition="start" :required="$required"/>
        <x-lareon::editor.input :label="__('url')" name="{{$finalName}}[url]" :value="$value['url'] ?? null" labelPosition="start" dir="ltr" :required="$required" placeholder="https://example.com/profile/author | /profile/author "/>
    </div>
</fieldset>
