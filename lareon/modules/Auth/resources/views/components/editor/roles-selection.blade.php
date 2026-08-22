@props(['name'=>'roles[]', 'value'=>null , 'multiple'=>false , 'labelPosition'=>'top' , 'label'=>__('roles') ,'required'=>true ,'inline'=>true])
@php
    Cache::forget('roles');
       $roles = Cache::has('roles')
       ?   \Illuminate\Support\Facades\Cache::get('roles')
       :   \Illuminate\Support\Facades\Cache::remember('roles' ,36000 , fn()=>\Teksite\Authorize\Models\Role::query()->pluck('title' ,'id')->toArray() )
@endphp
<x-lareon::editor.input-select  :multiple="$multiple" :labelPosition="$labelPosition" :label="$label" name="{{$name}}" :value="$value ?? null" :required="$required" :inline="$inline">
    @foreach($roles as $id=>$title)
        <option value="{{$id}}">{{$title}}</option>
    @endforeach
</x-lareon::editor.input-select>
