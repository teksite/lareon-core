<?php

namespace Lareon\Modules\Notifier\App\Http\Controllers\Web\Admin\Notifiers;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Notifier\App\Events\AwarenessEvent;
use Lareon\Modules\Notifier\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Notifier\App\Http\Requests\Admin\NewNotificationRequest;
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
            title: $data['title'],
            message: $data['message'],
            roleIds: $data['roles'],
            userIds: $data['users'],
            channels: $data['via'],
        );
    }

    /**
     * Display the specified resource.
     */
    public function show() {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit() {}

    /**
     * Update the specified resource in storage.
     *
     * @throws \Throwable
     */
    public function update() {}

    /**
     * Remove the specified resource from storage.
     *
     * @throws \Throwable
     */
    public function destroy() {}
}
