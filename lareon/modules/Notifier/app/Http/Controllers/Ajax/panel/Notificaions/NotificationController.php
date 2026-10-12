<?php

namespace Lareon\Modules\Notifier\App\Http\Controllers\Ajax\panel\Notificaions;

use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Notifier\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Notifier\App\Logics\NotificationLogic;
use Teksite\Handler\Facade\Responder;

class NotificationController extends Controller implements HasMiddleware
{

    public function __construct(public NotificationLogic $logic) {}

    public static function middleware()
    {
        return [
            new Middleware('can:panel.notification.read'),
        ];
    }

    public function markAsRead(DatabaseNotification $notification)
    {
        $res=$this->logic->markAsRead($notification);
        return Responder::fromResult($res)->reply();
    }
}
