@props(['name','value'=>[], 'required'=>true, 'arrayName'=>'applicantLocationRequirements' ,'title'=>__('applicant location requirements')])
@php
    $finalName = $name."[".$arrayName."]";
@endphp
<fieldset class="fieldset">
    <legend class="legend">{{$title}}</legend>
    <div class="space-y-6">
        <x-lareon::editor.input-select :label="__('country')" name="{{$finalName}}[addressCountry]" :value="$value['addressCountry'] ?? null" :required="true" labelPosition="top" :requried="$required">
            @foreach(\Lareon\Modules\Seo\App\Enums\Areas::cases() as $item)
                <option value="{{$item->name}}">{{$item->value}}</option>
            @endforeach
        </x-lareon::editor.input-select>
    </div>
</fieldset>
