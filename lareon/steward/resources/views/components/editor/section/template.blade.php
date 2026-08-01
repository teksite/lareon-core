@props(['required'=>false , 'type' , 'value'=>null , 'wrapperMode'=>null ] )
@php
    $wrapperClass=match ($wrapperMode){
        'x-box'=>'x-box',
        'y-box'=>'y-box',
        default => null
    };
   $templates = \Lareon\Modules\Meta\App\Models\MetaTemplate::query()->where('model_type', $type)->pluck('title' ,'id')->toArray();
@endphp


<section class="{{$wrapperClass}}">
    <x-lareon::inputs.label for="template_selector" :title="__('template')" :required="$required" class="mb-3"/>
    <x-lareon::inputs.select id="template_selector" class="block mt-1 w-full" name="template_id" aria-label="{{__('template selector')}}">
        <option value="" {{old('template', $value) ===null ? 'selected' :'' }}>{{__('default')}}</option>
        @foreach($templates as $id=>$temp)
            <option value="{{$id}}" {{old('template', $value) === $id ? 'selected' :'' }}>{{$temp}}</option>
        @endforeach
    </x-lareon::editor.input-select>
    <x-lareon::inputs.error :messages="$errors->get('template')" class="mt-2"/>
</section>
