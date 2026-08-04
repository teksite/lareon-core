@props([
    'title'=>'image',
    'id' => null,
    'name' => 'image',
    'value' => null,
    'old'=>true,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'label' => null,
    'error' => null,
    'wrapperClass' => null,
    'placeholder'=>'image',
    'preview'=>true,
    'size'=>null,
    'wrapperMode'=>null
])
@php

    $wrapperClass=match ($wrapperMode){
         'x-box'=>'x-box',
         'y-box'=>'y-box',
         default => null
     };

     $dotName = str_replace(['[', ']'], ['.', ''], $name);
     $finalId = ($id ?? $dotName).rand(100,999);
     $hasError = $errors->has($dotName) || $error;
     $errorMessage = $error ?? ($errors->first($dotName) ?? null);

     $errorStyle = $hasError ? 'input-error' : '';

     $consideredValue= $old ? old($dotName , $value) : $value ;

    if ($consideredValue)  $previewImage = \Teksite\FileManager\Models\UploadFile::find($consideredValue)?->url;

     $placeholder = match ($placeholder){
         'avatar'=>['src'=>'/assets/images/avatar-default.avif' ,'width'=>300 , 'height'=>300],
         default=>['src'=>'/assets/images/image-default.avif' ,'width'=>600 , 'height'=>400]
     };


@endphp

<div class="w-full relative {{ $wrapperClass }}" data-single-image>
    <x-lareon::inputs.label :title="$label" for="input_{{$finalId}}" class="mb-1" :markAsRequire="$required"/>
    @if($preview)
        <img data-filemanager-preview-id="input_{{$finalId}}" data-placehoder="{{$placeholder['src']}}" src="{{$previewImage ?? $placeholder['src']}}" alt="{{$label ?? __('select an image')}}" width="{{$placeholder['width'] ?? 300}}" height="{{$placeholder['height'] ?? 200}}">
    @endif
    <div>
        <x-lareon::inputs.text name="{{$name}}" id="input_{{$finalId}}" :value="$consideredValue" :disabled="$disabled" :required="$required" :readonly="$readonly" class="{{$errorStyle}}" dir="ltr" placeholder="{{$placeholder['src']}}" {{$attributes}}/>
        <x-lareon::inputs.error :messages="$errorMessage ?? null"/>
        <button role="button" type="button" data-delete-btn class="text-red-600 bg-red-50  min-w-fit w-fit rounded-xl text-xs font-semibold absolute top-5 left-5 p-0.5">x {{__('delete')}}</button>
        <x-lareon::buttons.simple :fullWidth="true" color="gray" variant="outline" role="button" type="button" class="imageBtn" data-filemanager data-type="object" id="button_{{$finalId}}" data-id="input_{{$finalId}}">
            {{$title}}
        </x-lareon::buttons.simple>
    </div>

</div>

