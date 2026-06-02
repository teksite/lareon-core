<?php

namespace Lareon\Modules\User\App\Http\Controllers\Web\Admin\Users;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Auth\App\Logics\PermissionLogic;
use Lareon\Modules\User\App\Events\UserCrudEvent;
use Lareon\Modules\User\App\Http\Requests\Admin\NewUserRequest;
use Lareon\Modules\User\App\Http\Requests\Admin\UpdateUserRequest;
use Lareon\Modules\User\App\Logics\UserLogic;
use Lareon\Modules\User\App\Models\User;
use Lareon\Steward\App\Enums\CrudTypeEnum;
use Lareon\Steward\App\Http\Controllers\Controller;
use Teksite\Authorize\Models\Role;
use Teksite\Handler\Facade\Responder;


class UsersACLController extends Controller implements HasMiddleware
{

    public function __construct(public UserLogic $logic) {}

    public static function middleware()
    {
        return [
            new Middleware('can:admin.user.acl.edit', only: ['edit', 'update']),
        ];
    }


    public function edit(User $user)
    {
        $permissions=(new PermissionLogic())->tree();
        $roles= Role::query()->pluck('title','id')->toArray();
        return view('user::admin.pages.users.acl', compact('user' ,'permissions' , 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws \Throwable
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $res = $this->logic->update($user, $request->validated());

        if ($res->success) {
            $this->logic->markAsVerified($user, $request->validated('email_verified_at'), $request->validated('phone_verified_at'));
            event(new UserCrudEvent($user, CrudTypeEnum::UPDATE, $request->validated()));
            return Responder::success(trans('lareon::global.updated_successfully', ['attribute' => __('user')]))->go();
        }
        return Responder::failed(trans('lareon::global.updated_failed', ['attribute' => __('user')]))->go();

    }
}
