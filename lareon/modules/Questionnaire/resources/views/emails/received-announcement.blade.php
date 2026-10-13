<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="auto">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $heading }}</title>
</head>
<body style="margin: 0; padding: 24px; font-family: Arial, sans-serif; color: #333; line-height: 1.8;">

<div style="max-width: 600px; margin: 0 auto;">

    <h2>{{ $heading }}</h2>

    <p>
        {{ __('thank you for contacting :site and filling out the form', [
            'site' => $appName,
        ]) }}
    </p>

    <p>
        {{ __('our colleagues will review the information you submitted and take the next steps, and if needed, they will contact you') }}
    </p>

    @if($explanation)
        <div>
            {!! $explanation !!}
        </div>
    @endif

    <p>
        {{ __('thanks') }},<br>
        {{ $appName }}
    </p>

</div>

</body>
</html>
