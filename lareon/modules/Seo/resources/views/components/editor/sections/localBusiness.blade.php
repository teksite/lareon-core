@props(['value'=>[] ,'name'=>'seo'])
@php
    $finalName = $name."[localBusiness]";
@endphp

<div class="grid gap-6 lg:grid-cols-2">
    <div class="space-y-6">
        <x-lareon::editor.input-select labelPosition="start" :label="__('type')" name="{{$finalName}}[localBusiness][type]" :value="$value['localBusiness']['type'] ?? null" :required="true">
            @foreach(\Lareon\Modules\Seo\App\Schema\SchemaOption::get('localBusiness_type') as $group=>$items)
                <optgroup label="{{$group}}">
                    @foreach($items as $key=>$desc)
                        <option value="{{$key}}">{{$desc}}</option>
                    @endforeach
                </optgroup>
            @endforeach
        </x-lareon::editor.input-select>

        <x-lareon::editor.input labelPosition="start" :label="__('title')" name="{{$finalName}}[localBusiness][title]" :value="$value['localBusiness']['title'] ?? null" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('website')])"/>
        <x-lareon::editor.input labelPosition="start" :label="__('alternate name')" name="{{$finalName}}[localBusiness][alternateName]" :value="$value['localBusiness']['alternateName'] ?? null" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('website')])"/>
        <x-lareon::editor.input-textarea labelPosition="start" :label="__('description')" name="{{$finalName}}[localBusiness][description]" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('website')])">{{$value['description'] ?? null}}</x-lareon::editor.input-textarea>

        <x-lareon::editor.input labelPosition="start" :label="__('image')" name="{{$finalName}}[localBusiness][image]" :value="$value['localBusiness']['image'] ?? null" placeholder="https://exmaple.com//images/image.jpg | /images/image.jpg" dir="ltr" :required="true"/>
        <x-lareon::editor.input labelPosition="start" :label="__('logo')" name="{{$finalName}}[localBusiness][logo]" :value="$value['localBusiness']['logo'] ?? null" placeholder="https://exmaple.com//images/logo.jpg | /images/logo.jpg" dir="ltr" :required="true"/>

        <x-lareon::editor.input labelPosition="start" :label="__('email')" name="{{$finalName}}[localBusiness][email]" :value="$value['localBusiness']['email'] ?? null" placeholder="example@example.com" :required="false" type="email" dir="ltr"/>
        <x-lareon::editor.input-phone labelPosition="start" :label="__('phone')" name="{{$finalName}}[localBusiness][telephone]" :value="$value['localBusiness']['telephone'] ?? null" placeholder="xx xxxx xx xx" :required="true"/>
        <x-lareon::editor.input-phone labelPosition="start" :label="__('fax')" name="{{$finalName}}[localBusiness][faxNumber]" :value="$value['localBusiness']['faxNumber'] ?? null" placeholder="xx xxxx xx xx" :required="false"/>
        <x-seo::price-range labelPosition="start" name="{{$finalName}}[localBusiness][priceRange]" :value="$value['priceRange'] ?? null" :required="true"/>
        <x-lareon::editor.input labelPosition="start" :label="__('map')" name="{{$finalName}}[localBusiness][hasMap]" :value="$value['localBusiness']['hasMap'] ?? null" placeholder="example@example.com" :required="false"/>

        <x-seo::editor.partials.address :value="$value['address'] ?? []" :required="true" :name="$finalName"/>
        <x-seo::editor.partials.geo :value="$value['geo'] ?? []" :required="false" :name="$finalName"/>
    </div>
    <div class="space-y-6">

        <x-seo::editor.partials.potential-action :value="$value['potentialAction'] ?? []" :required="false" :name="$finalName"/>
        <x-seo::editor.partials.same-as :value="$value['sameAs'] ?? []" :required="false" :name="$finalName"/>
        <x-seo::editor.partials.opening-hours-specification :value="$value['openingHoursSpecification'] ?? []" :required="false" :name="$finalName"/>
    </div>
</div>
