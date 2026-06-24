<?php

use Illuminate\Support\Facades\Route;
use Lareon\Steward\App\Http\Controllers\Web\Admin\DashboardController;
use Lareon\Steward\App\Http\Controllers\Web\Admin\Settings\CacheController;
use Lareon\Steward\App\Http\Controllers\Web\Admin\Settings\LogsController;
use Lareon\Steward\App\Http\Controllers\Web\Admin\Settings\MaintenanceModeController;
use Lareon\Steward\App\Http\Controllers\Web\Admin\Settings\SystemInformationController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

