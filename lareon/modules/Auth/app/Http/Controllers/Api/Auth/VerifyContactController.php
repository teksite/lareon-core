<?php

namespace Lareon\Modules\Auth\App\Http\Controllers\Api\Auth;

use Illuminate\Contracts\Container\BindingResolutionException;
use Lareon\Modules\Auth\App\Enums\ContactType;
use Lareon\Modules\Auth\App\Http\Controllers\Controller;
use Lareon\Modules\Auth\App\Http\Requests\Api\VerifyContactApiRequest;
use Lareon\Modules\User\App\Logics\UserLogic;
use Teksite\Handler\Actions\ServiceWrapper;
use Teksite\Handler\Facade\Responder;


class VerifyContactController extends Controller
{
    public function __construct(protected UserLogic $userLogic) {}


    /**
     * @throws BindingResolutionException
     * @throws \Throwable
     */
    public function verify(VerifyContactApiRequest $request)
    {
        $contactType = $request->contactType;
        $user = $request->user;

        $res = ServiceWrapper::make()->do(function () use ($contactType, $user) {
            $column = $contactType === ContactType::EMAIL ? 'email_verified_at' : 'phone_verified_at';
            return $user->forceFill([$column, now()])->save();
        })->run();


        if ($res->suucess) {
            return Responder::success(trans('lareon::global.crud.success.general'))->reply();

        }
        return Responder::failed(trans('lareon::errors.server_error_unknown'))->reply();
    }

}
