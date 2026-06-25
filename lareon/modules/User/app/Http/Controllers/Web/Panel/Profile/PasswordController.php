<?php

namespace Lareon\Modules\User\App\Http\Controllers\Web\Panel\Profile;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\User\App\Events\UserCrudEvent;
use Lareon\Modules\User\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\User\App\Http\Requests\Admin\NewUserRequest;
use Lareon\Modules\User\App\Http\Requests\Admin\UpdateUserRequest;
use Lareon\Modules\User\App\Logics\UserLogic;
use Lareon\Modules\User\App\Models\User;
use Lareon\Steward\App\Enums\CrudTypeEnum;
use Teksite\Handler\Facade\Responder;

class PasswordController extends Controller implements HasMiddleware
{
    public User|Authenticatable $user;

    public function __construct(public UserLogic $logic)
    {
        $this->user = auth()->user();
    }

    public static function middleware()
    {
        return [
            new Middleware('can:panel.profile.edit'),
            new Middleware('can:panel.profile.delete', only: ['destroy']),
        ];
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        return view('user::panel.pages.profile.password', ['user'=>$this->user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws \Throwable
     */
    public function update(UpdateUserRequest $request)
    {

    }

}
