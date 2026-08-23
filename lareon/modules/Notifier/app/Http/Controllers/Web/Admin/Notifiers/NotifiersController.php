<?php

namespace Lareon\Modules\Notifier\App\Http\Controllers\Web\Admin\Notifiers;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Notifier\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Notifier\App\Http\Requests\Admin\NewNotificationRequest;
use Lareon\Modules\Notifier\App\Logics\NotificationLogic;
use Teksite\Handler\Facade\Responder;

class NotifiersController extends Controller implements HasMiddleware
{

    public function __construct(public NotificationLogic $logic) {}

    public static function middleware()
    {
        return [
            new Middleware('can:admin.notification.read'),
            new Middleware('can:admin.notification.create', only: ['create', 'store']),
            new Middleware('can:admin.notification.edit', only: ['edit', 'update']),
            new Middleware('can:admin.notification.delete', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @throws \Throwable
     */
    public function index()
    {
//        $res = $this->logic->all();
//        $users = $res->result;
//        return view('notifier::admin.pages.notifications.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('notifier::admin.pages.notifications.create' );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws \Throwable
     */
    public function store(NewNotificationRequest $request)
    {
        $res = $this->logic->prepare($request->validated());


    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
//        if ($user->path()) return redirect()->to($user->path());
//        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
//        return view('user::admin.pages.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws \Throwable
     */
    public function update()
    {
//        $res = $this->logic->update($user, $request->validated());
//
//        if ($res->success) {
//            $this->logic->markAsVerified($user, $request->validated('email_verified_at'), $request->validated('phone_verified_at'));
//            event(new UserCrudEvent($user, CrudTypeEnum::UPDATE, $request->validated()));
//            return Responder::success(trans('lareon::global.crud.success.updated', ['attribute' => __('user')]))->go();
//        }
//        return Responder::failed(trans('lareon::global.crud.error.updated', ['attribute' => __('user')]))->go();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws \Throwable
     */
    public function destroy(User $user)
    {
//        $res = $this->logic->delete($user);
//
//        if ($res->success) {
//            event(new UserCrudEvent($user, CrudTypeEnum::DELETE));
//            return Responder::success(trans('lareon::global.crud.success.deleted', ['attribute' => __('user')]))->route('admin.users.index')->go();
//        }
//        return Responder::failed(trans('lareon::global.crud.error.deleted', ['attribute' => __('user')]))->go();
    }
}
