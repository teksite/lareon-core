<?php

use Illuminate\Support\Facades\Route;
use Lareon\Modules\User\App\Http\Controllers\Web\Panel\Users\UsersController;


Route::prefix('profile')->name('users.')->group(function () {
    Route::get('/', [UsersController::class, 'edit'])->name('edit');
});
