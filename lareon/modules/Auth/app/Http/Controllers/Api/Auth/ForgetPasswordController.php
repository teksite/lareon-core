<?php

namespace Lareon\Modules\Auth\App\Http\Controllers\Api\Auth;

use Illuminate\Contracts\Container\BindingResolutionException;
use Lareon\Modules\Auth\App\Http\Controllers\Controller;
use Lareon\Modules\Auth\App\Http\Requests\Api\ForgottenPasswordApiRequest;
use Lareon\Modules\User\App\Logics\UserLogic;
use Teksite\Handler\Facade\Responder;


class ForgetPasswordController extends Controller
{
    public function __construct() {}


    /**
     * reset password
     * @throws \Throwable
     */
    public function reset(ForgottenPasswordApiRequest $request)
    {
        $res = (new UserLogic())->changePassword($request->user, $request->validated());

        return $res->success
            ? Responder::success(trans('lareon::global.crud.success.update', ['attribute' => __('password')]))->reply()
            : Responder::failed(trans('lareon::global.crud.failed.update', ['attribute' => __('password')]))->reply();
    }

}
