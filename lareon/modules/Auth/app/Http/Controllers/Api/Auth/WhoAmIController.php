<?php

namespace Lareon\Modules\Auth\App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Lareon\Modules\Auth\App\Http\Controllers\Controller;
use Lareon\Modules\User\App\Http\Resources\UserResource;
use Teksite\Handler\Facade\Responder;


class WhoAmIController extends Controller
{
    public function __construct() {}

    public function whoAmI(Request $request)
    {
        return Responder::Success(':)', [
            'user' => UserResource::make(auth('sanctum')->user()),
        ])->reply();
    }

    public function authorize(Request $request)
    {
        $user = auth('sanctum')->user();
        return Responder::Success(':)', [
            'basic'              => UserResource::make(auth('sanctum')->user()),
            'roles'             => $user->roles->pluck('title')->toArray(),
            'permissions'       => $user->permissions->pluck('title')->toArray(),
            'roles_permissions' => array_values($user->getAllPermissions(false)),
        ])->reply();
    }

}
