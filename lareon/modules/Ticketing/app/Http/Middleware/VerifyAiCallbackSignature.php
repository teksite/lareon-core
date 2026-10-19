<?php

namespace Lareon\Modules\Ticketing\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class VerifyAiCallbackSignature
{
    public function handle(Request $request, Closure $next): Response
    {
        $secret = (string) config('ticketing.ai.shared_secret');

        abort_if($secret === '', 500, 'کلید امنیتی هوش مصنوعی در ماژول تیکتینگ تنظیم نشده است.');

        $signature = (string) $request->header('X-Ticketing-Signature');
        $expected = hash_hmac('sha256', $request->getContent(), $secret);

        abort_unless(hash_equals($expected, $signature), 401, 'امضای درخواست نامعتبر است.');

        return $next($request);
    }
}
