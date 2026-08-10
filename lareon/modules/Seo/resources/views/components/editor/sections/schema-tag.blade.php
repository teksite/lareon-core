@props(['data'=>[] ])
@php
    $name='seo[schema]';
    $data=collect($data)->toArray();
@endphp
<section>
    <div class="bg-slate-50 p-6 bordering rounded-lg space-y-6">
        <x-lareon::editor.input-select labelPosition="top" :label="__('schema type')" name="{{$name}}[type]" :value="$data['type'] ?? 'webPage'" :required="true">
            @foreach(\Lareon\Modules\Seo\App\Schema\SchemaOption::get('page_types') as $key=>$desc)
                <option value="{{$key}}">{{__($desc)}}</option>
            @endforeach
        </x-lareon::editor.input-select>
        <x-seo::editor.types.article name="{{$name}}" :value="$data['schema'] ?? []"/>
    </div>
</section>
