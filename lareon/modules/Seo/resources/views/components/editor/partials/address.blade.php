@props(['name','value'=>[], 'required'=>true  ])

<fieldset class="fieldset">
    <legend class="legend">{{__('location')}}</legend>
    <div class="space-y-6">
        <x-lareon::editor.input :label="__('streetAddress')" name="{{$name}}[location][streetAddress]" :value="$value['streetAddress'] ?? null" labelPosition="start" :required="$required"/>
        <x-lareon::editor.input :label="__('address locality')" name="{{$name}}[location][addressLocality]" :value="$value['addressLocality'] ?? null" labelPosition="start"/>
        <x-lareon::editor.input :label="__('address region')" name="{{$name}}[location][addressRegion]" :value="$value['addressRegion'] ?? null" labelPosition="start"/>
        <x-lareon::editor.input :label="__('postal code')" name="{{$name}}[location][postalCode]" :value="$value['postalCode'] ?? null" labelPosition="start"/>
        <x-lareon::editor.input-select :label="__('addressCountry')" name="{{$name}}[localBusiness][addressCountry]" :value="$localBusiness['addressCountry'] ?? null" :required="true" labelPosition="start" :requried="$required">
            @foreach(\Lareon\Modules\Seo\App\Enums\Areas::cases() as $key=>$item)
                <option value="{{$key}}">{{$item}}</option>
            @endforeach
        </x-lareon::editor.input-select>
    </div>
</fieldset>
