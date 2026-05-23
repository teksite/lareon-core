@props(['name' ,'column'=>null ,'value'=>null ,"mediaType"=>'image'])
@php
    $rand=rand(1000,9999).\Illuminate\Support\Str::random(6);
@endphp
<div class="">
    <div id="prev_{{$rand}}" class="p-1 rounded-lg border border-zinc-300 grid sm:grid-cols-2 xl:grid-cols-4 gap-1">
        @foreach(json_decode($value ,true) ?? [] as $item)
            <img src="{{$item}}" alt="item galllery" class="w_100" loading="lazy" fetchpriority="low" decoding="async">
        @endforeach
    </div>
    <x-lareon::input.text class="hidden" name="{{$name}}" :value="$value ?? ''" id="input_{{$rand}}" />
    <x-lareon::button.solid id="btn_{{$rand}}" data-id="{{$rand}}" data-load="{{config('mediamanager.routes.ajax.prefix' , '/tkadmin/ajax/appearance/file-media')}}" data-modal_url="{{route('admin.ajax.appearance.media.modal')}}" type="button" data-type="{{$mediaType}}" class="border border-blue-600 w-full block galleryChoose" color="white">
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
