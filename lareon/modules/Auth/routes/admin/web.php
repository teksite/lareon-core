<?php

use Illuminate\Support\Facades\Route;
use Lareon\Modules\Auth\App\Http\Controllers\Web\Admin\Authorization\PermissionsController;
use Lareon\Modules\Auth\App\Http\Controllers\Web\Admin\Authorization\RolesController;
use Lareon\Modules\Auth\App\Http\Controllers\Web\Admin\OAuths\OAuthsController;


Route::prefix('authorize')->name('authorize.')->group(function () {
    Route::resource('permissions', PermissionsController::class);
    Route::resource('roles', RolesController::class);
});


Route::prefix('settings/oauth')->name('settings.oauth.')->group(function(){
    Route::get('/', [OAuthsController::class, 'edit'])->name('edit');
    Route::patch('/', [OAuthsController::class, 'update'])->name('update');
});
