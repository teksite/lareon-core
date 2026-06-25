<?php

use Illuminate\Support\Facades\Route;
use Lareon\Modules\User\App\Http\Controllers\Web\Panel\Profile\ProfileController;
use Lareon\Modules\User\App\Http\Controllers\Web\Panel\Users\UsersController;


Route::prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'edit'])->name('edit');
});

//Route::resource('users', UsersController::class);

