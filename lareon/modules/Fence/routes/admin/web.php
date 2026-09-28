<?php

use Illuminate\Support\Facades\Route;
use Lareon\Modules\Fence\App\Http\Controllers\Web\Admin\Ips\IpsController;

Route::name('settings.ips.')->prefix('settings/ips')->group(function () {
    Route::get('/', [IpsController::class, 'index'])->name('index');
    Route::post('/', [IpsController::class, 'store'])->name('store');
    Route::delete('/{ip}', [IpsController::class, 'destroy'])->name('destroy');
});
