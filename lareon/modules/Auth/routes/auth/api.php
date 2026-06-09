<?php

use Lareon\Modules\Auth\App\Http\Controllers\Api\Auth\RegisterUserController;
use Lareon\Modules\Auth\App\Http\Controllers\Api\Auth\TokenController;

//TODO add throttle and rate limiter

Route::prefix('verification-code')->name('verification_code.')->group(function () {
    Route::post("send", [TokenController::class, 'send',])->name('send');
    Route::post("verify", [TokenController::class, 'verify',])->name('verify');
});


Route::middleware(['guest'])->group(function () {
    Route::post("/register", [RegisterUserController::class, 'store'])->name('register');
//    Route::post("/login", [LoginController::class, 'login'])->name('login');
//    Route::post("/forgot-password", [ForgotPasswordController::class, 'forgot'])->name('forgot-password');
});

