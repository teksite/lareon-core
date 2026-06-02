<?php

use Illuminate\Support\Facades\Route;
use Lareon\Modules\User\App\Http\Controllers\Web\Admin\Users\UsersACLController;
use Lareon\Modules\User\App\Http\Controllers\Web\Admin\Users\UsersController;


Route::prefix('users/acl/{user}')->name('users.acl.')->group(function () {
    Route::get('/', [UsersAclController::class, 'edit'])->name('edit');
    Route::patch('/', [UsersAclController::class, 'update'])->name('update');
});
Route::resource('users', UsersController::class);
