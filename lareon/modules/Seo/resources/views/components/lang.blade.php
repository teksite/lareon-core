@props(['name' , 'value'=>[], "inline"=>false, "required"=>true ,'labelPosition' => 'start' , 'multiple'=>true])

<x-lareon::editor.input-select :inline="$inline" :required="$required" :label="__('language')" name="{{$name}}" :value="$value" :labelPosition="$labelPosition" :multiple="$multiple">
    @foreach(\Lareon\Modules\Seo\App\Schema\SchemaOption::get('language_list') as $key=>$item)
        <option value="{{$key}}" >
            {{__($item)}}
        </option>
    @endforeach
</x-lareon::editor.input-select>
