@props(['value'=>[] ,'name'=>'seo', 'arrayName'=>'organization'])
@php
    $finalName = $name."[".$arrayName."]";
@endphp

<div class="grid gap-6 lg:grid-cols-2">
    <div class="space-y-6">
        <x-lareon::editor.input-select labelPosition="start" :label="__('type')" name="{{$finalName}}[organization][type]" :value="$value['organization']['type'] ?? null" :required="true">
            @foreach(\Lareon\Modules\Seo\App\Schema\SchemaOption::get('organization_type') as $group=>$items)
                <optgroup label="{{$group}}">
                    @foreach($items as $key=>$desc)
                        <option value="{{$key}}">{{$desc}}</option>
                    @endforeach
                </optgroup>
            @endforeach
        </x-lareon::editor.input-select>

        <x-lareon::editor.input labelPosition="start" :label="__('title')" name="{{$finalName}}[organization][title]" :value="$value['organization']['title'] ?? null" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('website')])"/>
        <x-lareon::editor.input labelPosition="start" :label="__('alternate name')" name="{{$finalName}}[organization][alternateName]" :value="$value['organization']['alternateName'] ?? null" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('website')])"/>
        <x-lareon::editor.input labelPosition="start" :label="__('legal name')" name="{{$finalName}}[organization][legalName]" :value="$value['organization']['legalName'] ?? null" />
        <x-lareon::editor.input-textarea labelPosition="start" :label="__('description')" name="{{$finalName}}[organization][description]" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('website')])">{{$value['organization']['description'] ?? null}}</x-lareon::editor.input-textarea>

        <x-lareon::editor.input labelPosition="start" :label="__('image')" name="{{$finalName}}[organization][image]" :value="$value['organization']['image'] ?? null" placeholder="https://exmaple.com//images/image.jpg | /images/image.jpg" dir="ltr" :required="true"/>
        <x-lareon::editor.input labelPosition="start" :label="__('logo')" name="{{$finalName}}[organization][logo]" :value="$value['organization']['logo'] ?? null" placeholder="https://exmaple.com//images/logo.jpg | /images/logo.jpg" dir="ltr" :required="true"/>

        <x-seo::editor.partials.number-of-employees :value="$value['numberOfEmployees'] ?? []" :required="false" :name="$finalName"/>

    </div>
    <div class="space-y-6">
        <x-seo::editor.partials.same-as :value="$value['sameAs'] ?? []" :required="false" :name="$finalName"/>
    </div>
</div>
<div>
    <x-seo::editor.partials.contact-point :value="$value['ContactPoint'] ?? []" :required="false" :name="$finalName"/>
</div>
