<!doctype html>
<html lang="{{app()->getLocale()}}" dir="{{is_rtl() ? 'rtl': 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{asset('/uploads/favicon/favicon.ico')}}" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="16x16" href="{{asset('/uploads/favicon/favicon-16x16.png')}}">
    <link rel="apple-touch-icon" sizes="32x32" href="{{asset('/uploads/favicon/favicon-32x32.png')}}">
    <link rel="apple-touch-icon" sizes="192x192" href="{{asset('/uploads/favicon/favicon-192x192.png')}}">
    <meta name="theme-color" content="#ffffff">
    <title>@yield('title',__('dashboard')) - {{__(config('app.name'))}} - Lareon</title>
    @vite(['lareon/steward/resources/css/panel.css','lareon/steward/resources/js/panel.js'])
    @stack('headerScripts')
</head>
<body class="bg-slate-200 text-sm overflow-y-scroll" x-data="{sidebar:true ,togglesSidebar() { this.sidebar = !this.sidebar }}">
<main class="">
    @include('lareon::panel.layouts.partials.aside')
    <div class="ms-auto me-0  transition-all duration-100 xl:w-8/9" :class="{'xl:w-8/9' : sidebar }">
        @include('lareon::panel.layouts.partials.header')
        @include('lareon::panel.layouts.partials.upper-header')
        @include('lareon::panel.layouts.partials.errors')
        <div class="p-3">
        {!! $slot !!}

        </div>
    </div>
</main>
@include('lareon::panel.layouts.partials.footer')
@include('lareon::panel.layouts.partials.response')

@stack('footerScripts')
</body>
</html>
