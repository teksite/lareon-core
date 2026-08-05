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
        <x-lareon::editor.input-textarea labelPosition="start" :label="__('description')" name="{{$finalName}}[localBusiness][description]" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('website')])">{{$value['description'] ?? null}}</x-lareon::editor.input-textarea>
        <x-lareon::editor.input labelPosition="start" :label="__('image')" name="{{$finalName}}[localBusiness][image]" :value="$value['localBusiness']['image'] ?? null" placeholder="https://exmaple.com//images/image.jpg | /images/image.jpg" dir="ltr" :required="true"/>
        <x-lareon::editor.input labelPosition="start" :label="__('logo')" name="{{$finalName}}[localBusiness][logo]" :value="$value['localBusiness']['logo'] ?? null" placeholder="https://exmaple.com//images/logo.jpg | /images/logo.jpg" dir="ltr" :required="true"/>
        <x-lareon::editor.input labelPosition="start" :label="__('email')" name="{{$finalName}}[localBusiness][email]" :value="$value['localBusiness']['email'] ?? null" placeholder="example@example.com" :required="false" type="email" dir="ltr"/>
        <x-lareon::editor.input-phone labelPosition="start" :label="__('phone')" name="{{$finalName}}[localBusiness][telephone]" :value="$value['localBusiness']['telephone'] ?? null" placeholder="xx xxxx xx xx" :required="true"/>
        <x-lareon::editor.input-phone labelPosition="start" :label="__('fax')" name="{{$finalName}}[localBusiness][faxNumber]" :value="$value['localBusiness']['faxNumber'] ?? null" placeholder="xx xxxx xx xx" :required="false"/>
        <x-seo::price-range labelPosition="start" name="{{$finalName}}[localBusiness][priceRange]" :value="$value['priceRange'] ?? null" :required="true"/>
        <x-lareon::editor.input labelPosition="start" :label="__('map')" name="{{$finalName}}[localBusiness][hasMap]" :value="$value['localBusiness']['hasMap'] ?? null" placeholder="example@example.com" :required="false"/>

    </div>
    <div class="space-y-6">
        <x-seo::editor.partials.address :value="$value['address'] ?? []" :required="true" :name="$finalName"/>
        <x-seo::editor.partials.geo :value="$value['geo'] ?? []" :required="false" :name="$finalName"/>
        <x-seo::editor.partials.potential-action :value="$value['potentialAction'] ?? []" :required="false" :name="$finalName"/>
        <x-seo::editor.partials.same-as :value="$value['sameAs'] ?? []" :required="false" :name="$finalName"/>
        <x-seo::editor.partials.opening-hours-specification :value="$value['openingHoursSpecification'] ?? []" :required="false" :name="$finalName"/>
    </div>
</div>

{{--<div class="mb-3 flex items-center gap-1">--}}
{{--    <x-lareon::input.checkbox id="state" name="local_business[state]" value="1"--}}
{{--                              :checked="old('local_business.state' , $data->state ?? false)"/>--}}
{{--    <x-lareon::input.label for="state" :title="__('activate')"/>--}}
{{--</div>--}}
{{--<div class="mb-3 grid gap-3 md:grid-cols-2">--}}
{{--    <div>--}}
{{--        <x-lareon::input.label for="{{$rand}}_country" :title="__('country')"/>--}}
{{--        <x-lareon::input.select name="{{$name}}[location][country]" id="{{$rand}}_country" :required="true">--}}
{{--            @foreach(config('area') as $key=>$vl)--}}
{{--                <option value="{{$key}}"--}}
{{--                    {{isset($value['country']) && $value['country']=== $key ? 'selected' :''}}>--}}
{{--                    {{__($vl)}}--}}
{{--                </option>--}}
{{--            @endforeach--}}
{{--        </x-lareon::input.select>--}}
{{--        <x-lareon::input.error :messages="get_error($errors , '{{$name}}[location][country]')"/>--}}
{{--    </div>--}}
{{--    City--}}
{{--    <div class="">--}}
{{--        <x-lareon::input.label for="{{$rand}}_city" :title="__('city')"/>--}}
{{--        <x-lareon::input.text id="{{$rand}}_city" name="{{$name}}[location][city]"  :value="$value['city'] ?? ''" :placeholder="__('city')" :required="true"/>--}}
{{--        <x-lareon::input.error :messages="get_error($errors , '{{$name}}[location][city]')"/>--}}
{{--    </div>--}}
{{--</div>--}}
{{--<div class="mb-3 grid gap-3 md:grid-cols-2">--}}
{{--    Street--}}
{{--    <div >--}}
{{--        <x-lareon::input.label for="{{$rand}}_name" :title="__('venue')"/>--}}
{{--        <x-lareon::input.text id="{{$rand}}_name" name="{{$name}}[location][name]"  :value="$value['name'] ?? ''" :placeholder="__('venue')" :required="true"/>--}}
{{--        <x-lareon::input.error :messages="get_error($errors , '{{$name}}[location][name]')"/>--}}
{{--    </div>--}}
{{--    Zip Code--}}
{{--    <div >--}}
{{--        <x-lareon::input.label for="{{$rand}}_zip_code" :title="__('zip code')"/>--}}
{{--        <x-lareon::input.text id="{{$rand}}_zip_code" dir="ltr" type="number" name="{{$name}}[location][zip_code]"  :value="$value['zip_code'] ?? ''" :placeholder="__('zip code')" :required="true"/>--}}
{{--        <x-lareon::input.error :messages="get_error($errors , '{{$name}}[location][zip_code]')"/>--}}
{{--    </div>--}}
{{--</div>--}}
{{--<div class="mb-3">--}}
{{--    <x-lareon::input.label for="{{$rand}}_street" :title="__('street')"/>--}}
{{--    <x-lareon::input.text id="{{$rand}}_street" name="{{$name}}[location][street]"  :value="$value['street'] ?? ''" :placeholder="__('street')" :required="true"/>--}}
{{--    <x-lareon::input.error :messages="get_error($errors , '{{$name}}[location][street]')"/>--}}
{{--</div>--}}

