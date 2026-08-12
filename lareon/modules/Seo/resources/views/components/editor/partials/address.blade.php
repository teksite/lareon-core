@props(['name','value'=>[], 'required'=>true, 'arrayName'=>'address' ,'title'=>__('address')])
@php
    $finalName = $name."[".$arrayName."]";
@endphp
<fieldset class="fieldset">
    <legend class="legend">{{$title}}</legend>
    <div class="space-y-6">
        <x-lareon::editor.input :label="__('street')" name="{{$finalName}}[streetAddress]" :value="$value['streetAddress'] ?? null" labelPosition="start" :required="$required"/>
        <x-lareon::editor.input :label="__('locality')" name="{{$finalName}}[addressLocality]" :value="$value['addressLocality'] ?? null" labelPosition="start"/>
        <x-lareon::editor.input :label="__('region')" name="{{$finalName}}[addressRegion]" :value="$value['addressRegion'] ?? null" labelPosition="start"/>
        <x-lareon::editor.input :label="__('postal code')" name="{{$finalName}}[postalCode]" :value="$value['postalCode'] ?? null" labelPosition="start" type="number" dir="ltr"/>
        <x-lareon::editor.input-select :label="__('country')" name="{{$finalName}}[addressCountry]" :value="$value['addressCountry'] ?? null" :required="true" labelPosition="start" :requried="$required">
            @foreach(\Lareon\Modules\Seo\App\Enums\Areas::cases() as $item)
                <option value="{{$item->name}}">{{$item->value}}</option>
            @endforeach
        </x-lareon::editor.input-select>
    </div>
</fieldset>
