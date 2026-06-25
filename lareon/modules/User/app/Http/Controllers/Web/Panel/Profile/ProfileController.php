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

class ProfileController extends Controller implements HasMiddleware
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

    public function show()
    {
        if ($this->user->path()) return redirect()->to($this->user->path());
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        return view('user::panel.pages.profile.edit', ['user'=>$this->user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws \Throwable
     */
    public function update(UpdateUserRequest $request)
    {
        $res = $this->logic->update($this->user, $request->validated());

        if ($res->success) {
            $this->logic->markAsVerified($this->user, $request->validated('email_verified_at'), $request->validated('phone_verified_at'));
            event(new UserCrudEvent($this->user, CrudTypeEnum::UPDATE, $request->validated()));
            return Responder::success(trans('lareon::global.updated_successfully', ['attribute' => __('user')]))->go();
        }
        return Responder::failed(trans('lareon::global.updated_failed', ['attribute' => __('user')]))->go();

    }


    /**
     * Remove the specified resource from storage.
     *
     * @throws \Throwable
     */
    public function destroy()
    {
        $res = $this->logic->delete($this->user);

        if ($res->success) {
            event(new UserCrudEvent($this->user, CrudTypeEnum::DELETE));
            return Responder::success(trans('lareon::global.delete_successfully', ['attribute' => __('user')]))->route('panel.users.index')->go();
        }
        return Responder::failed(trans('lareon::global.delete_failed', ['attribute' => __('user')]))->go();
    }
}
