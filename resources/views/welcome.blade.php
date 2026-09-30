<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @fonts

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
    @endif
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
{!! captcha_field('math', ['name' => 'captcha_b']) !!}
{!! captcha_field('custom', ['name' => 'captcha_c']) !!}
{!! captcha_field('mini', ['name' => 'captcha_a']) !!}

@captchaField('flat')
@captchaField('inverse')
@captchaField('flat')

@captchaScript
</body>
</html>
