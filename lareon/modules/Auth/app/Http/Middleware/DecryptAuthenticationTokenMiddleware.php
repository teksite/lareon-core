<?php

namespace Lareon\Modules\Auth\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Lareon\Modules\Auth\App\Services\AuthTokenService;
use Teksite\Handler\Facade\Responder;

class DecryptAuthenticationTokenMiddleware
{

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {

        $headerToken = $request->header('authorization') ?? $request->header('Authorization');
        $cookieToken = $request->cookies->get(AuthTokenService::PREFIX);
        if (!!$headerToken || !!$cookieToken) {

            $res = false;
            if (!!$headerToken) {
                $token = str_replace(AuthTokenService::TOKEN_PREFIX, '', $headerToken);
                $res = $this->setInHeader($request, $token);
            } elseif (is_null($headerToken) && !!$cookieToken) {
                $res = $this->setInHeader($request, $cookieToken);
            }

            if (!$res) {
                return Responder::Failed(['server_error' => trans('auth::messages.verification_code.invalid_auth_token')])->reply();
            }
        }


        return $next($request);


    }

    /**
     * @param Request $request
     * @param $token
     * @return bool
     */
    private function setInHeader(Request $request, $token): bool
    {
        try {
            $token = AuthTokenService::ENCRYPT_TOKEN ? Crypt::decrypt($token) : $token;
            $request->headers->set('Authorization', "Bearer $token");
            return true;
        } catch (\Exception $exception) {
            if (app()->isLocal()) Log::error($exception);
            return false;
        }
    }
}
