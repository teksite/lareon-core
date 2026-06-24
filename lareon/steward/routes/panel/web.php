<?php

use Illuminate\Support\Facades\Route;
use Lareon\Steward\App\Http\Controllers\Web\Panel\DashboardController;
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

