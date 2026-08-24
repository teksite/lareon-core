<?php

namespace Lareon\Modules\Notifier\App\Http\Controllers\Web\Panel\Notifiers;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Notifier\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Notifier\App\Logics\NotificationLogic;

class NotifiersController extends Controller implements HasMiddleware
{

    public function __construct(public NotificationLogic $logic) {}

    public static function middleware()
    {
        return [
            new Middleware('can:panel.notification.read'),
            new Middleware('can:panel.notification.create', only: ['create', 'store']),
            new Middleware('can:panel.notification.edit', only: ['edit', 'update']),
            new Middleware('can:panel.notification.delete', only: ['destroy']),
        ];
    }

    public function index()
    {
    }

    public function show()
    {

    }
}
