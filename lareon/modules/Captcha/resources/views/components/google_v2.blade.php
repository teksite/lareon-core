@php
    $siteKet=config('lareon.captcha.google_site_key');
    $siteSecret=config('lareon.captcha.google_secret_key');
@endphp

@if($siteKet && $siteSecret)
    <div {!! $attributes->merge(['class' => 'g-recaptcha']) !!} data-sitekey="{{$siteKet}}"></div>
    @if ($errors->get('g-recaptcha-response'))
        <ul {{ $attributes->merge(['class' => 'text-sm text-red-600 space-y-1 mt-1']) }}>
            @foreach ($messages as $message)
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
    <script src="https://www.google.com/recaptcha/api.js?hl={{app()->getLocale()}}" async defer></script>
@endpush

