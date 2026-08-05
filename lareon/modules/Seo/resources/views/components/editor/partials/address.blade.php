@props(['name','value'=>[], 'required'=>true  ])

<fieldset class="fieldset">
    <legend class="legend">{{__('address')}}</legend>
    <div class="space-y-6">
        <x-lareon::editor.input :label="__('streetAddress')" name="{{$name}}[address][streetAddress]" :value="$value['streetAddress'] ?? null" labelPosition="start" :required="$required"/>
        <x-lareon::editor.input :label="__('address locality')" name="{{$name}}[address][addressLocality]" :value="$value['addressLocality'] ?? null" labelPosition="start"/>
        <x-lareon::editor.input :label="__('address region')" name="{{$name}}[address][addressRegion]" :value="$value['addressRegion'] ?? null" labelPosition="start"/>
        <x-lareon::editor.input :label="__('postal code')" name="{{$name}}[address][postalCode]" :value="$value['postalCode'] ?? null" labelPosition="start" type="number" dir="ltr"/>
        <x-lareon::editor.input-select :label="__('addressCountry')" name="{{$name}}[address][addressCountry]" :value="$value['addressCountry'] ?? null" :required="true" labelPosition="start" :requried="$required">
            @foreach(\Lareon\Modules\Seo\App\Enums\Areas::cases() as $key=>$item)
                <option value="{{$key}}">{{$item}}</option>
            @endforeach
        </x-lareon::editor.input-select>
    </div>
</fieldset>
