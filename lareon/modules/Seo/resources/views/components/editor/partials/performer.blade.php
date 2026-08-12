@props(['name','value'=>[], 'required'=>false , 'arrayName'=>'performer' ,'title'=>__('performer')])
@php
    $finalName = $name."[".$arrayName."]";
@endphp
<fieldset class="fieldset">
    <legend class="legend">{{$title}}</legend>
        <div class="grid gap-6 md:grid-cols-2">
            <x-lareon::editor.input-select labelPosition="start" :label="__('performer type')" name="{{$finalName}}[type]" :value="$value['type'] ?? null" :required="$required">
                @foreach(\Lareon\Modules\Seo\App\Schema\SchemaOption::get('performer_type') as $key=>$desc)
                    <option value="{{$key}}">{{__($desc)}}</option>
                @endforeach
            </x-lareon::editor.input-select>
            <x-lareon::editor.input :label="__('name')" name="{{$finalName}}[name]" :value="$value['name'] ?? null" labelPosition="start" :required="$required" />
        </div>
</fieldset>
