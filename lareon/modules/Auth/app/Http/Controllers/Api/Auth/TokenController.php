<?php

namespace Lareon\Modules\Auth\App\Http\Controllers\Api\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Lareon\Modules\Auth\App\Enums\ContactType;
use Lareon\Modules\Auth\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Auth\App\Http\Requests\Api\RegisterApiRequest;
use Lareon\Modules\Auth\App\Http\Requests\Api\SendTokenApiRequest;
use Lareon\Modules\Auth\App\Services\ActionTokenService;
use Lareon\Modules\User\App\Logics\UserLogic;
use Teksite\Handler\Facade\Responder;


class TokenController extends Controller
{
    public function __construct() {}


    /**
     * @throws \Throwable
     */
    public function send(SendTokenApiRequest $request)
    {

    }


    public function verify(RegisterApiRequest $request)
    {

    }

}
