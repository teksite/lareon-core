<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ClearCacheDailyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $cacheKey = 'last_cache_clear_date';


        if (!Cache::has($cacheKey)) {
            $this->runCommands();
            Cache::put($cacheKey, now()->toDateString(), now()->addDay());
        }
        return $next($request);
    }

    private function runCommands(): void
    {
        try {

            Artisan::call('cache:clear');
            Artisan::call('cache:flush-all');
            Artisan::call('auth:clear-resets');


        }catch (\Exception $exception){
            Log::info('clearing cache daily middleware');
            Log::error($exception->getMessage());
        }
    }
}
