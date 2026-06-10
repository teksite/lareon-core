<?php

namespace Lareon\Modules\Auth\App\Http\Controllers\Api\Auth;

use Lareon\Modules\Auth\App\Http\Controllers\Controller;
use Lareon\Modules\Auth\App\Http\Requests\Api\CheckUserApiRequest;
use Teksite\Handler\Facade\Responder;


class CheckUserController extends Controller
{
    public function __construct() {}


    /**
     * @throws \Throwable
     */
    public function check(CheckUserApiRequest $request)
    {
       $user = $request->user;
       $result = !!$user ? trans('auth::messages.auth.user_exists') : trans('auth::messages.auth.user_not_found');
       return Responder::success($result ,[])->reply();

    }

}
