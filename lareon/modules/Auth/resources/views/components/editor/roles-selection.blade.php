@props(['name'=>'roles[]', 'value'=>null , 'multiple'=>false , 'labelPosition'=>'top' , 'label'=>__('roles') ,'required'=>true])
@php
@
@endphp

<x-lareon::editor.input-select :labelPosition="$labelPosition" :label="$roles" name="{{$name}}" :value="$value ?? null" :required="$required">
    @foreach(\Lareon\Modules\Seo\App\Schema\SchemaOption::get('timezone_list') as $key=>$desc)
        <option value="{{$desc}}">{{__($key)}} : {{$desc}}</option>
    @endforeach
</x-lareon::editor.input-select>
