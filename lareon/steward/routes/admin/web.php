<?php

use Illuminate\Support\Facades\Route;
use Lareon\Steward\App\Http\Controllers\Web\Admin\DashboardController;
use Lareon\Steward\App\Http\Controllers\Web\Admin\Settings\SystemInformationController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::name('settings.')->prefix('settings')->group(function (){
    Route::get('/information', [SystemInformationController::class, 'index'])->name('information.index');
    Route::get('/cache', [SystemInformationController::class, 'index'])->name('cache.index');
});
