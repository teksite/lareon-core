<?php

namespace Lareon\Modules\Auth\App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Lareon\Modules\Auth\App\Http\Controllers\Controller;
use Lareon\Modules\User\App\Http\Resources\UserResource;
use Teksite\Handler\Facade\Responder;


class WhoAmIController extends Controller
{
    public function __construct()
    {
    }


    public function whoAmI(Request $request)
    {
        return Responder::Success(':)' , UserResource::make(auth('sanctum')->user()));
    }

}
