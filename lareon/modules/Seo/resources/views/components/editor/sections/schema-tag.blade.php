@props(['data'=>[] , 'name'=> 'seo[schema]'])
@php
    $data=collect($data)->toArray();
    $schemaType=$data['type'] ?? 'WebPage';
    $schemaData=$data['schema'] ?? ['WebPage'];
@endphp
<section>
    <div class="bg-slate-50 p-6 bordering rounded-lg space-y-6">
        <x-lareon::editor.input-select labelPosition="top" :label="__('schema type')" name="{{$name}}[type]" :value="$schemaType" :required="true" data-schema-selector>
            @foreach(\Lareon\Modules\Seo\App\Schema\SchemaOption::get('page_types') as $key=>$desc)
                <option value="{{$key}}">{{__($desc)}}</option>
            @endforeach
        </x-lareon::editor.input-select>

        <x-seo::editor.types.schema-views :type="$schemaType" :data="$schemaData ?? []"/>

    </div>
</section>

@push('headerScripts')
    @vite(['lareon/modules/Seo/resources/js/app.js'])
@endpush
