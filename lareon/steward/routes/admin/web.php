<?php

use Illuminate\Support\Facades\Route;
use Lareon\Steward\App\Http\Controllers\SystemInformationController;
use Lareon\Steward\App\Http\Controllers\Web\Admin\DashboardController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::name('admin.settings.')->prefix('settings')->group(function (){
    Route::get('/', [SystemInformationController::class, 'index'])->name('information.index');

});
