<?php

use Illuminate\Support\Facades\Route;
use Lareon\Modules\Notifier\App\Http\Controllers\Web\Panel\Notifiers\NotifiersController;

Route::prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotifiersController::class, 'index'])->name('index');
    Route::get('/{notification}', [NotifiersController::class, 'show'])->name('show');
});
