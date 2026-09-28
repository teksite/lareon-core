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
    <x-lareon::editor.input-select :value="$value" :required="$required" :label="__('template')" name="template_id" aria-label="{{__('template selector')}}">
        <option value="">{{__('default')}}</option>
        @foreach($templates as $id=>$temp)
            <option value="{{$id}}">{{$temp}}</option>
        @endforeach
    </x-lareon::editor.input-select>
</section>
