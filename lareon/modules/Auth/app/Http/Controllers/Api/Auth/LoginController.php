<?php

namespace Lareon\Modules\Auth\App\Http\Controllers\Api\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Carbon;
use Lareon\Modules\Auth\App\Enums\ContactType;
use Lareon\Modules\Auth\App\Http\Controllers\Controller;
use Lareon\Modules\Auth\App\Http\Requests\Api\LoginApiRequest;
use Lareon\Modules\Auth\App\Services\AuthTokenService;
use Lareon\Modules\User\App\Http\Resources\UserResource;
use Lareon\Modules\User\App\Models\User;
use Teksite\Handler\Actions\ServiceWrapper;
use Teksite\Handler\Facade\Responder;


class LoginController extends Controller
{
    public function __construct(protected AuthTokenService $authService) {}


    /**
     * @throws BindingResolutionException
     * @throws \Throwable
     */
    public function login(LoginApiRequest $request)
    {
        $user = $request->user;
        $contactType = $request->contactType;

        $res = ServiceWrapper::make()->do(function () use ($contactType, $user) {
            $this->verifyingContactType($user , $contactType);
            return $this->authService->create($user);
        })->run();

        if ($res->success) {
            $token = $res->result;

            return Responder::Success(trans('auth::messages.auth.login_success'),
                ['user' => UserResource::make($user), 'token' => $token,]
            )->reply()
                     ->withCookie( cookie(
                         AuthTokenService::PREFIX,
                         $token,
                         AuthTokenService::TTL,
                         config('session.domain'),
                         null,
                         true,
                         true,
                     ));

        }
        Responder::failed(trans('auth::messages.auth.login_failed'))->reply();

    }


    public function verifyingContactType(User|Authenticatable $user , ?ContactType $contactType = null, bool $overwrite = false, ?Carbon $date = null): void
    {
        if (is_null($contactType)) return;
        $date ??= Carbon::now();

        $way = $contactType->value . '_verified_at';
        if ($overwrite || is_null($user->$way)) {
            $user->forceFill([$way => $date])->save();
        }
    }
}
