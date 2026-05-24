<?php

namespace Lareon\Modules\User\App\Http\Controllers\Web\Admin\Users;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\User\App\Events\UserCrudEvent;
use Lareon\Modules\User\App\Http\Requests\Admin\NewUserRequest;
use Lareon\Modules\User\App\Http\Requests\Admin\UpdateUserRequest;
use Lareon\Modules\User\App\Logics\UserLogic;
use Lareon\Modules\User\App\Models\User;
use Lareon\Steward\App\Http\Controllers\Controller;
use Teksite\Handler\Facade\Responder;


class UsersController extends Controller implements HasMiddleware
{

    public function __construct(public UserLogic $logic)
    {
    }

    public static function middleware()
    {
        return [
            new Middleware('can:admin.user.read'),
            new Middleware('can:admin.user.create', only: ['create', 'store']),
            new Middleware('can:admin.user.edit', only: ['edit', 'update']),
            new Middleware('can:admin.user.delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     * @throws \Throwable
     */
    public function index()
    {
        $res = $this->logic->all();
        $users = $res->result;
        return view('lareon::admin.pages.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('lareon::admin.pages.users.create');
    }

    /**
     * Store a newly created resource in storage.
     * @throws \Throwable
     */
    public function store(NewUserRequest $request)
    {
        $res = $this->logic->create($request->validated());

        if ($res->success) {
            event(new UserCrudEvent($res->result, 'create'), $request->validated());
            return Responder::success(trans('lareon::global.created_successfully' ,['attribute' => __('user')]));
        }
        return Responder::failed(trans('lareon::global.created_failed' ,['attribute' => __('user')]));

    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
       if ($user->path()) redirect()->to($user->path());

        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('lareon::admin.pages.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     * @throws \Throwable
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $res = $this->logic->update($user ,$request->validated());

        if ($res->success) {
            return Responder::success(trans('lareon::global.updated_successfully' ,['attribute' => __('user')]));
        }
        return Responder::failed(trans('lareon::global.updated_failed' ,['attribute' => __('user')]));

    }

    /**
     * Remove the specified resource from storage.
     * @throws \Throwable
     */
    public function destroy(User $user)
    {
        $res = $this->logic->delete($user);

        if ($res->success) {
            return Responder::success(trans('lareon::global.delete_successfully' ,['attribute' => __('user')]));
        }
        return Responder::failed(trans('lareon::global.delete_failed' ,['attribute' => __('user')]));
    }
}
