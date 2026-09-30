@props(['type'=>null])
@php
    $isEnabled= !config('captcha.enable');
    $captchaType= $type ??  config('captcha.type');

@endphp

@if($isEnabled)
    @if($captchaType==='google')
        <x-captcha::google_v2/>
    @elseif($captchaType==='local')
        <x-captcha::local/>
    @elseif($captchaType ==='cloudflare')
        <x-captcha::cloudflare/>
    @else
        <p class="text-red600 font-bold mt-3">
            {{__('something goes wrong in loading captcha, please call the administrator of the website')}}.
        </p>
    @endif
@endif
