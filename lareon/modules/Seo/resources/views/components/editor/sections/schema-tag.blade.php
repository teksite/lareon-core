@props(['data'=>[] ])
@php
    $name='seo[schema]';
    $data=collect($data)->toArray();
    $file= (config('seo.schema',[]))[$data['type'] ?? 'WebPage'] ?? 'web-page'
@endphp
<section>
    <div class="bg-slate-50 p-6 bordering rounded-lg space-y-6">
        <x-lareon::editor.input-select labelPosition="top" :label="__('schema type')" name="{{$name}}[type]" :value="$data['type'] ?? 'WebPage'" :required="true">
            @foreach(\Lareon\Modules\Seo\App\Schema\SchemaOption::get('page_types') as $key=>$desc)
                <option value="{{$key}}">{{__($desc)}}</option>
            @endforeach
        </x-lareon::editor.input-select>


        <x-dynamic-component :component="'seo::editor.types.' . $file" :name="$name" :value="$data['schema'] ?? []"/>
    </div>
</section>
