<?php

namespace Lareon\Modules\Auth\App\Http\Controllers\Api\Auth;

use Lareon\Modules\Auth\App\Http\Controllers\Controller;
use Lareon\Modules\Auth\App\Http\Requests\Api\RegisterApiRequest;
use Lareon\Modules\Auth\App\Services\AuthTokenService;
use Lareon\Modules\User\App\Events\UserCrudEvent;
use Lareon\Modules\User\App\Logics\UserLogic;
use Lareon\Steward\App\Enums\CrudTypeEnum;
use Teksite\Handler\Facade\Responder;


class RegisterUserController extends Controller
{
    public function __construct(protected AuthTokenService $authService, protected UserLogic $logic) {}


    /**
     * @throws \Throwable
     */
    public function store(RegisterApiRequest $request)
    {
        $name = $request->input('name');
        $lastname = $request->input('lastname');
        $password = $request->input('password');
        $contactType = $request->contactType;
        $contactValue = $request->contactValue;
        $contactAltType = $request->contactAltType;
        $contactAltValue = $request->contactAltValue;

        $data = [
            'name'                 => $name,
            'lastname'             => $lastname,
            'password'             => $password,
            $contactType->value    => $contactValue,
            $contactAltType->value => $contactAltValue,
        ];

        $res = $this->logic->create($data);

        if ($res->success) {
            $user = $res->result;
            $apiToken = $this->authService->create($user);
            event(new UserCrudEvent($res->result, CrudTypeEnum::CREATE, $request->validated()));


            return Responder::Success(trans('lareon::global.crud.success.created', ['attribute' => __('user')]))
                            ->reply()->withCookie(cookie('x_web_token', $apiToken, 24 * 28 * 60, config('session.domain'), null, true, true));
        }
        return Responder::failed(trans('lareon::global.crud.error.created', ['attribute' => __('user')]))->reply();
    }

}
