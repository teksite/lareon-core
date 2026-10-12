<?php

use Illuminate\Support\Facades\Route;
use Lareon\Modules\Notifier\App\Http\Controllers\Ajax\panel\Notificaions\NotificationController;

Route::post('/client-submit/notification/read' ,[NotificationController::class, 'markAsRead'])->name('client-submit.notification.read');
