@props(['name' , 'value'=>[], "inline"=>false, "required"=>true ,'labelPosition' => 'start'])

<x-lareon::editor.input-select :inline="$inline" :required="$required" :label="__('currency')" name="{{$name}}" :value="$value" :labelPosition="$labelPosition">
    @foreach(\Lareon\Modules\Seo\App\Schema\SchemaOption::get('currency_list') as $key=>$item)
        <option value="{{$key}}">
            {{__($item)}}
        </option>
    @endforeach
</x-lareon::editor.input-select>
