<?php

use Illuminate\Support\Facades\Route;
use Lareon\Modules\Auth\App\Http\Controllers\Web\Admin\Authorization\PermissionsController;
use Lareon\Modules\Auth\App\Http\Controllers\Web\Admin\Authorization\RolesController;


Route::prefix('authorize')->name('authorize.')->group(function () {
    Route::resource('permissions', PermissionsController::class);
    Route::resource('roles', RolesController::class);
});
