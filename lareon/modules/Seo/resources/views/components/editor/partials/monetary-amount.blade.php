@props(['name','value'=>[], 'required'=>false , 'arrayName'=>'MonetaryAmount' ,'title'=>__('monetary amount')])
@php
    $finalName = $name."[".$arrayName."]";
@endphp
<fieldset class="fieldset">
    <legend class="legend">{{$title}}</legend>
    <div class="space-y-6">
        <div class="grid gap-6 md:grid-cols-3">
            <x-lareon::editor.input :label="__('min salary')" min="0" type="number" name="{{$finalName}}[QuantitativeValue][minValue]" :value="$value['QuantitativeValue']['minValue'] ?? null" labelPosition="top" :required="$required"/>
            <x-lareon::editor.input :label="__('max salary')" min="0" type="number" name="{{$finalName}}[QuantitativeValue][maxValue]" :value="$value['QuantitativeValue']['maxValue'] ?? null" labelPosition="top" :required="$required"/>
            <x-seo::currency :label="__('currency')" min="0" type="number" name="{{$finalName}}[currency]" :value="$value['currency'] ?? null" labelPosition="top" :required="$required"/>
        </div>
        <x-lareon::editor.input-select labelPosition="top" :label="__('per')" name="{{$finalName}}[QuantitativeValue][unitText]" :value="$value['QuantitativeValue']['unitText'] ?? null" :required="$required">
            @foreach(\Lareon\Modules\Seo\App\Schema\SchemaOption::get('per_time_list') as $key=>$desc)
                <option value="{{$key}}">{{__($desc)}}</option>
            @endforeach
        </x-lareon::editor.input-select>
    </div>
</fieldset>
