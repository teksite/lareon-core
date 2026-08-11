@props(['name','value'=>[], 'required'=>false , 'arrayName'=>'event'])
@php
    $finalName = $name."[".$arrayName."]";
@endphp
<fieldset class="fieldset">
    <legend class="legend">{{__('event')}}</legend>
    <div class="space-y-6">
        <div class="grid gap-6 md:grid-cols-2">
            <x-lareon::editor.input :label="__('name')" name="{{$finalName}}[name]" :value="$value['name'] ?? null" labelPosition="start" :required="$required" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('meta')])"/>
            <x-lareon::editor.input :label="__('image')" dir="ltr" name="{{$finalName}}[image]" :value="$value['image'] ?? null" labelPosition="start" :required="$required" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('main image')])"/>
        </div>
        <x-lareon::editor.input-textarea :label="__('description')" name="{{$finalName}}[description]" labelPosition="start" :required="$required" :placeholder="__('lareon::global.placeholders.empty.read',['attribute'=>__('meta')])">{{$value['description'] ?? null}}</x-lareon::editor.input-textarea>
        <div class="grid gap-6 md:grid-cols-3">
            <x-lareon::editor.input type="datetime-local" :label="__('start date')" name="{{$finalName}}[startDate]" :value="$value['startDate'] ?? null" labelPosition="start" :required="$required"/>
            <x-lareon::editor.input type="datetime-local" :label="__('end date')" name="{{$finalName}}[endDate]" :value="$value['endDate'] ?? null" labelPosition="start" :required="$required"/>
            <x-lareon::editor.input-select labelPosition="start" :label="__('timezone')" name="{{$finalName}}[timezone]" :value="$value['timezone'] ?? null" :required="$required">
                @foreach(\Lareon\Modules\Seo\App\Schema\SchemaOption::get('timezone_list') as $key=>$desc)
                    <option value="{{$desc}}">{{__($key)}} : {{$desc}}</option>
                @endforeach
            </x-lareon::editor.input-select>
        </div>

        <div class="grid gap-6 md:grid-cols-2">

            <x-lareon::editor.input-select labelPosition="start" :label="__('event status')" name="{{$finalName}}[eventStatus]" :value="$value['eventStatus'] ?? null" :required="$required">
                @foreach(\Lareon\Modules\Seo\App\Schema\SchemaOption::get('event_status_list') as $key=>$desc)
                    <option value="{{$desc}}">{{__($key)}}</option>
                @endforeach
            </x-lareon::editor.input-select>
            <x-lareon::editor.input-select labelPosition="start" :label="__('event attendance mode')" name="{{$finalName}}[eventAttendanceMode]" :value="$value['eventAttendanceMode'] ?? null" :required="$required">
                @foreach(\Lareon\Modules\Seo\App\Schema\SchemaOption::get('event_attendance_mode') as $key=>$desc)
                    <option value="{{$desc}}">{{__($key)}}</option>
                @endforeach
            </x-lareon::editor.input-select>
        </div>

    </div>
</fieldset>
