<?php

use Illuminate\Support\Facades\Route;
use Lareon\Modules\Notifier\App\Http\Controllers\Web\Admin\Notifiers\NotifiersController;

Route::resource('notifications', NotifiersController::class);
