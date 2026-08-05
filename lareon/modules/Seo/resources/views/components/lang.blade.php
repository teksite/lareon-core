@props(['name' , 'value'=>[], "inline"=>false, "required"=>true ,'labelPosition' => 'start' , 'multiple'=>true])

<x-lareon::editor.input-select :inline="$inline" :required="$required" :label="__('language')" name="{{$name}}" :value="$value" :labelPosition="$labelPosition" :multiple="$multiple">
    @foreach(\Lareon\Modules\Seo\App\Enums\Langs::cases() as $lang)
        <option value="{{$lang->name}}" >
            {{__($lang->value)}}
        </option>
    @endforeach
</x-lareon::editor.input-select>
