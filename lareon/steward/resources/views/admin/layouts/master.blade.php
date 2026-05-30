<!doctype html>
<html lang="{{app()->getLocale()}}" dir="{{is_rtl() ? 'rtl': 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{asset('/uploads/favicon/favicon.ico')}}" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="16x16" href="{{asset('"/uploads/favicon/favicon-16x16.png')}}">
    <link rel="apple-touch-icon" sizes="32x32" href="{{asset('/uploads/favicon/favicon-32x32.png')}}">
    <link rel="apple-touch-icon" sizes="192x192" href="{{asset('/uploads/favicon/favicon-192x192.png')}}">
    <meta name="theme-color" content="#ffffff">
    <title>@yield('title',__('dashboard')) - {{__(config('app.name'))}} - Lareon</title>
    @vite(['lareon/steward/resources/css/app.css','lareon/steward/resources/js/app.js'])
    @stack('headerScripts')
</head>
<body class="bg-slate-200 text-sm overflow-y-scroll" x-data="{sidebar:true ,togglesSidebar() { this.sidebar = !this.sidebar }}">
<main class="p-3">
    @include('lareon::admin.layouts.partials.aside')
    <div class="ms-auto me-0 p-3 transition-all duration-100 xl:w-5/6" :class="{'xl:w-5/6' : sidebar }">
        @include('lareon::admin.layouts.partials.upper-header')
        @include('lareon::admin.layouts.partials.header')
        @include('lareon::admin.layouts.partials.errors')
        {!! $slot !!}
    </div>
</main>
@include('lareon::admin.layouts.partials.footer')
@stack('footerScripts')
</body>
</html>
