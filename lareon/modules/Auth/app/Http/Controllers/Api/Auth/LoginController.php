<?php

namespace Lareon\Modules\Auth\App\Http\Controllers\Api\Auth;

use Illuminate\Contracts\Container\BindingResolutionException;
use Lareon\Modules\Auth\App\Http\Controllers\Controller;
use Lareon\Modules\Auth\App\Http\Resources\UserResource;
use Lareon\Modules\Auth\App\Services\AuthTokenService;
use Teksite\Handler\Actions\ServiceWrapper;
use Teksite\Handler\Facade\Responder;


class LoginController extends Controller
{
    public function __construct(protected AuthTokenService $authService) {}


    /**
     * @throws BindingResolutionException
     * @throws \Throwable
     */
    public function login(LoginRequest $request)
    {
        $user = $request->user;
        $contactType = $request->contactType;

        $res = ServiceWrapper::make()->do(function () use ($contactType, $user) {
            $user->verifyingContactType($contactType);
            return $this->authService->create($user);
        })->run();
        if ($res->success) {
            $token = $res->result;

            Responder::Success(trans('auth::messages.auth.login_success'),
                ['user' => UserResource::make($user), 'token' => $token,]
            )->reply()
            ->withCookie(cookie('x_web_token', $token, AuthTokenService::TTL, null, true, true));

        }
        Responder::failed(trans('auth::messages.auth.login_failed'))->reply();

    }
}
