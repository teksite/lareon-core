<!DOCTYPE html>
<html lang="{{app()->getLocale()}}" dir="{{is_rtl() ? 'rtl': 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['lareon/steward/resources/css/app.css','lareon/steward/resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 ">
    <div class="mb-6">
        <a href="/" class="text-center flex flex-col items-center gap-3">
            <x-lareon::logo class="w-32 h-auto mx-auto"/>
            <span class="font-bold text-lg">LAREON</span>
        </a>
    </div>

    <x-lareon::box class="w-full max-w-2xl">
        {{ $slot }}
    </x-lareon::box>
</div>
</body>
</html>
