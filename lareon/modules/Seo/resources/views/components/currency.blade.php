@props(['name' , 'value'=>[], "inline"=>false, "required"=>true ,'labelPosition' => 'start'])

<x-lareon::editor.input-select :inline="$inline" :required="$required" :label="__('currency')" name="{{$name}}" :value="$value" :labelPosition="$labelPosition">
    @foreach(\Lareon\Modules\Seo\App\Enums\Currencies::cases() as $currency)
        <option value="{{$currency->name}}">
            {{__($currency->value)}}
        </option>
    @endforeach
</x-lareon::editor.input-select>
