<?php

namespace Lareon\Modules\Notifier\App\Http\Controllers\Web\Admin\Notifiers;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Notifier\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Notifier\App\Http\Requests\Admin\NewNotificationRequest;
use Lareon\Modules\Notifier\App\Http\Requests\Admin\UpdateNotificationRequest;
use Lareon\Modules\Notifier\App\Jobs\PrepareAwarenessNotificationJob;
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
        $res = $this->logic->all();
        $notifications = $res->result;
        return view('notifier::admin.pages.notifications.index', compact('notifications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('notifier::admin.pages.notifications.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws \Throwable
     */
    public function store(NewNotificationRequest $request)
    {
        $data = $request->validated();
        PrepareAwarenessNotificationJob::dispatch(
            title   : $data['title'],
            message : $data['message'],
            roleIds : $data['roles'] ?? [],
            userIds : $data['users'] ?? [],
            channels: $data['channels'],
            creator : auth('admin')->id(),
        );

        return Responder::success()
                        ->title(trans('lareon::global.crud.success.created', ['attribute' => __('notification')]))
                        ->route('admin.notifications.index')->go();
    }

    /**
     * Display the specified resource.
     */
    public function show() {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DatabaseNotification $notification) {
        return view('notifier::admin.pages.notifications.edit', compact('notification'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws \Throwable
     */
    public function update(UpdateNotificationRequest $request, DatabaseNotification $notification) {

        $res= $this->logic->update($notification, $request->validated());

        return Responder::fromResult($res,
            trans('lareon::global.crud.success.updated', ['attribute' => __('page')]),
            trans('lareon::global.crud.error.updated', ['attribute' => __('page')]),
        )->go();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws \Throwable
     */
    public function destroy(DatabaseNotification $notification) {

        $res = $this->logic->delete($notification);

        return Responder::fromResult($res,
            trans('lareon::global.crud.success.deleted', ['attribute' => __('notification')]),
            trans('lareon::global.crud.error.deleted', ['attribute' => __('notification')]),
            route('admin.notifications.index', $res->result)
        )->go();
    }
}
