<?php

namespace Lareon\Modules\Ticketing\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyAiToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $secret = (string) config('ticketing.ai.shared_secret');

        abort_if($secret === '', 500, 'کلید امنیتی هوش مصنوعی در ماژول تیکتینگ تنظیم نشده است.');

        abort_unless(hash_equals($secret, (string) $request->bearerToken()), 401, 'توکن نامعتبر است.');

        return $next($request);
    }
}
