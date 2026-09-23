<?php

use Lareon\Steward\App\Http\Controllers\Web\Auth\AuthenticatedSessionController;

Route::get('/', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/', [AuthenticatedSessionController::class, 'store'])->name('store');
Route::delete('/', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
