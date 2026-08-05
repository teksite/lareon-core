@props(['value'=>[] ,'name'=>'seo'])
@php
    $localBusiness=$value['localBusiness'] ?? [];
    $address=$value['address'] ?? [];
    $geo=$value['geo'] ?? [];
    $potential=$value['potentialAction'] ?? [];
@endphp

<div class="space-y-6">
    <x-lareon::editor.input-select labelPosition="start" :label="__('type')" name="{{$name}}[localBusiness][type]" :value="$localBusiness['type'] ?? null" :required="true">
        @foreach(\Lareon\Modules\Seo\App\Schema\SchemaOption::get('localBusiness_type') as $group=>$items)
            <optgroup label="{{$group}}">
                @foreach($items as $key=>$desc)
                    <option value="{{$key}}">{{$desc}}</option>
                @endforeach
            </optgroup>
        @endforeach
    </x-lareon::editor.input-select>
    <x-lareon::editor.input labelPosition="start" :label="__('title')" name="{{$name}}[localBusiness][title]" :value="$localBusiness['title'] ?? null" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('website')])"/>
    <x-lareon::editor.input-textarea labelPosition="start" :label="__('description')" name="{{$name}}[localBusiness][description]" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('website')])">{{$localBusiness['description'] ?? null}}</x-lareon::editor.input-textarea>
    <x-lareon::editor.input labelPosition="start" :label="__('image')" name="{{$name}}[localBusiness][image]" :value="$localBusiness['image'] ?? null" placeholder="https://exmaple.com//images/image.jpg | /images/image.jpg" dir="ltr" :required="true"/>
    <x-lareon::editor.input labelPosition="start" :label="__('logo')" name="{{$name}}[localBusiness][logo]" :value="$localBusiness['logo'] ?? null" placeholder="https://exmaple.com//images/logo.jpg | /images/logo.jpg" dir="ltr" :required="true"/>
    <x-lareon::editor.input labelPosition="start" :label="__('email')" name="{{$name}}[localBusiness][email]" :value="$localBusiness['email'] ?? null" placeholder="example@example.com" :required="false" type="email"/>
    <x-lareon::editor.input-phone labelPosition="start" :label="__('phone')" name="{{$name}}[localBusiness][telephone]" :value="$localBusiness['telephone'] ?? null" placeholder="xx xxxx xx xx" :required="true"/>
    <x-lareon::editor.input-phone labelPosition="start" :label="__('fax')" name="{{$name}}[localBusiness][faxNumber]" :value="$localBusiness['faxNumber'] ?? null" placeholder="xx xxxx xx xx" :required="false"/>
    <x-seo::price-range labelPosition="start" name="{{$name}}[localBusiness][priceRange]" :value="$localBusiness['priceRange'] ?? null" :required="true"/>
    <x-lareon::editor.input labelPosition="start" :label="__('map')" name="{{$name}}[localBusiness][hasMap]" :value="$localBusiness['hasMap'] ?? null" placeholder="example@example.com" :required="false"/>

    <x-seo::editor.partials.address :value="$address" :required="true" :name="$name"/>
    <x-seo::editor.partials.geo :value="$geo" :required="false" :name="$name"/>
    <x-seo::editor.partials.potential-action :value="$potential" :required="false" :name="$name"/>
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

