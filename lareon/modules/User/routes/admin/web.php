<?php

use Illuminate\Support\Facades\Route;
use Lareon\Modules\User\App\Http\Controllers\Web\Admin\Users\UsersACLController;
use Lareon\Modules\User\App\Http\Controllers\Web\Admin\Users\UsersController;


Route::resource('users', UsersController::class);
Route::prefix('users/acl')->name('users.acl')->group(function () {
    Route::get('/', [UsersAclController::class, 'edit'])->name('edit');
    Route::get('/', [UsersAclController::class, 'update'])->name('update');
});
