<?php

use Illuminate\Support\Facades\Route;
use Lareon\Steward\App\Http\Controllers\Web\Admin\DashboardController;
use Lareon\Steward\App\Http\Controllers\Web\Admin\Settings\CacheController;
use Lareon\Steward\App\Http\Controllers\Web\Admin\Settings\LogsController;
use Lareon\Steward\App\Http\Controllers\Web\Admin\Settings\SystemInformationController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::name('settings.')->prefix('settings')->group(function () {
    Route::get('/information', [SystemInformationController::class, 'index'])->name('information.index');

    Route::name('cache.')->prefix('caching')->group(function () {
        Route::get('/', [CacheController::class, 'index'])->name('index');
        Route::post('/', [CacheController::class, 'execute'])->name('execute');
    });
    Route::name('logs.')->prefix('logs')->group(function () {
        Route::get('/', [LogsController::class, 'index'])->name('index');
        Route::patch('/', [LogsController::class, 'clear'])->name('clear');
        Route::delete('/', [LogsController::class, 'delete'])->name('destroy');
    });
});
