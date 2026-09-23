<?php

use Lareon\Steward\App\Http\Controllers\Web\Auth\AuthenticatedSessionController;

Route::get('tkadmin/login', [AuthenticatedSessionController::class ,'create'])->name('admin.login');
