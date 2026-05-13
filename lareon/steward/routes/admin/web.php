<?php

use Illuminate\Support\Facades\Route;
use Lareon\Steward\App\Http\Controllers\Web\Admin\DashboardController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
