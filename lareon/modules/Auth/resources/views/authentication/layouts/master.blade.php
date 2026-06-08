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
    <meta name="robots" content="{{isset($indexable) && $indexable ? 'follow,index' : 'nofollow,noindex' }}">
    <meta name="theme-color" content="#ffffff">
    <title>{{$title ?? __('authentication') }} - {{__(config('app.name'))}}</title>
    @vite(['lareon/steward/resources/css/panel.css','lareon/steward/resources/js/panel.js',
'lareon/modules/auth/resources/js/panel.js'])
    @stack('headerScripts')
</head>
<body class="bg-slate-200">
<main class="ms-auto me-0 max-h-svh h-svh min-h-svh bg-center bg-cover bg-no-repeat bg-wavy p-3">
    <div class="grid sm:grid-cols-2 xl:grid-cols-3 h-full items-stretch">
        <div class="y-box flex flex-col gap-6 justify-between w-full">
            <header>
                @yield('header')
            </header>
            <div>
                {!! $slot !!}
                <x-auth::auth.session-status/>
                <x-lareon::inputs.error/>
                <div id="resultMsg" class="text-sm font-semibold mt-6"></div>
            </div>
            <footer>
                @yield('footer')
            </footer>
        </div>

    </div>
    <div class="absolute top-5 end-5 ">
        @foreach($errors?->all() as $error)
            <div class="bg-white">
                {{$error}}
            </div>
        @endforeach
    </div>
</main>
@stack('footerScripts')
</body>
</html>
