@props(['data'=>[] ,'name'=>'seo[localBusiness][data]'])
@php
    $localBusiness=$data['localBusiness'] ?? [];
@endphp
<div class="space-y-6">
    <x-lareon::editor.input :label="__('title')" name="{{$name}}[title]" :value="$localBusiness['title'] ?? null" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('website')])"/>
    <x-lareon::editor.input-textarea :label="__('description')" name="{{$name}}[description]" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('website')])">{{$localBusiness['description'] ?? null}}</x-lareon::editor.input-textarea>
</div>


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

