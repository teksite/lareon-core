<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append([
            \App\Http\Middleware\ClearCacheDailyMiddleware::class,
            \Teksite\Extralaravel\Middleware\HoneypotMiddleware::class
            ]);
        $middleware->api(prepend:[\Lareon\Modules\Auth\App\Http\Middleware\DecryptAuthenticationTokenMiddleware::class ]);
        $middleware->web(prepend:[\Lareon\Steward\App\Http\Middleware\SetLocale::class ]);
    }

    )
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
