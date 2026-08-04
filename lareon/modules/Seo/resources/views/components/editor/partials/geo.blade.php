@props(['name','value'=>[]] )
@php
    $rand=rand(10,1000).\Illuminate\Support\Str::random(8).rand(10,1000)
@endphp
<fieldset class="fieldset">
    <legend class="legend">{{__('geo')}}</legend>
    {{--latitude--}}
    <div class="mb-3">
        <x-lareon::input.label for="{{$rand}}_latitude" :title="__('latitude')"/>
        <x-lareon::input.text id="{{$rand}}_latitude" name="{{$name}}[geo][latitude]"  dir="ltr" :value="$value['latitude'] ?? ''" :placeholder="__('latitude')" :required="true"/>
        <x-lareon::input.error :messages="get_error($errors , '{{$name}}[geo][latitude]')"/>
    </div>
    {{--longitude--}}
    <div class="mb-3">
            <x-lareon::input.label for="{{$rand}}_longitude" :title="__('longitude')"/>
            <x-lareon::input.text id="{{$rand}}_longitude" name="{{$name}}[geo][longitude]"  dir="ltr" :value=" $value['longitude'] ?? ''" :placeholder="__('longitude')" :required="true"/>
            <x-lareon::input.error :messages="get_error($errors , '{{$name}}[geo][longitude]')"/>
    </div>
</fieldset>
