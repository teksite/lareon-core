@php
    $siteKet=config('lareon.captcha.cloudflare_site_key');
    $siteSecret=config('lareon.captcha.cloudflare_secret_key');
@endphp

@if($siteKet && $siteSecret)
    <div  {!! $attributes->merge(['class' => 'cf-turnstile']) !!}  data-sitekey="{{$siteKet}}"></div>
    @if ($errors->get('g-recaptcha-response'))
        <ul {{ $attributes->merge(['class' => 'text-sm text-red-600 space-y-1 mt-1']) }}>
            @foreach ((array) $messages as $message)
                @if($message)
                    @foreach ((array) $message as $msg)
                        {{$msg}}
                    @endforeach
                @else
                    {{$message}}
                @endif
            @endforeach
        </ul>
    @endif
@endisset

@push('footerScripts')
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
@endpush
