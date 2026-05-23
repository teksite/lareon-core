@props(['name' ,'column'=>null , 'placeholder'=>null ,'value'=>null ,"mediaType"=>'image' , "showInput"=>true])
@php
    $rand=rand(1000,9999).\Illuminate\Support\Str::random(6);
    $plc=match (true){
        $placeholder==='avatar'=>  "/lareon/avatar.avif",
        default=>"/lareon/no-cover.avif"
    };
@endphp
<div class="">
    @if($placeholder && $mediaType==='image')
        <img src="{{$value ?? $plc}}" class="w-full h-auto" alt="avatar" loading="lazy" decoding="async" fetchpriority="low" width="200" height="200" id="prev_{{$rand}}">
    @elseif($placeholder && $mediaType==='video')
        <video src="{{$value ?? $plc}}" class="w-full h-auto" controls preload="none" id="prev_{{$rand}}"></video>
    @elseif($placeholder && $mediaType==='audio')
        <audio src="{{$value ?? $plc}}" class="w-full h-auto" controls preload="none" id="prev_{{$rand}}"></audio>
    @endif
    <x-lareon::input.text :class="$showInput ?:'hidden'" name="{{$name}}" :value="$value ?? ''" id="input_{{$rand}}" />
    <x-lareon::button.solid data-id="{{$rand}}" data-load="{{config('mediamanager.routes.ajax.prefix' , '/tkadmin/ajax/appearance/file-media')}}" data-modal_url="{{route('admin.ajax.appearance.media.modal')}}" type="button" data-type="{{$mediaType}}" class="border border-blue-600 w-full block mediaChoose" color="white">
        {{__('choose')}}
    </x-lareon::button.solid>
</div>

@once
    @push('footerScripts')
        <script src="{{asset('assets/js/gallery.js')}}"></script>
    @endpush
    @push('headerScripts')
        <link href="{{asset('assets/style/gallery.css')}}" rel="stylesheet">
    @endpush
@endonce
