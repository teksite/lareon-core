<?php

namespace Lareon\Steward\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locals = config('lareon.local' ,[]);

        foreach ($locals as $pattern => $local) {
            if ($local !==null && $request->routeIs($pattern)) {
                app()->setLocale($local);
                break;
            }
        }

        return $next($request);
    }
}

