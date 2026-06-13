<?php

namespace Lareon\Modules\Auth\App\Http\Controllers\Api\Auth;

use Illuminate\Contracts\Container\BindingResolutionException;
use Lareon\Modules\Auth\App\Http\Controllers\Controller;
use Lareon\Modules\Auth\App\Http\Requests\Api\ForgottenPasswordApiRequest;


class ForgetPasswordController extends Controller
{
    public function __construct() {}


    /**
     * @throws BindingResolutionException
     * @throws \Throwable
     */
    public function login(ForgottenPasswordApiRequest $request)
    {

    }

}
