<?php

use Lareon\Modules\Auth\App\Http\Controllers\Api\Auth\CheckUserController;
use Lareon\Modules\Auth\App\Http\Controllers\Api\Auth\LoginController;
use Lareon\Modules\Auth\App\Http\Controllers\Api\Auth\RegisterUserController;
use Lareon\Modules\Auth\App\Http\Controllers\Api\Auth\TokenController;
use Lareon\Modules\Auth\App\Http\Controllers\Api\Auth\VerifyContactController;
use Lareon\Modules\Auth\App\Http\Controllers\Api\Auth\WhoAmIController;
use Lareon\Modules\Auth\App\Http\Middleware\EnsureContactsAreVerifiedMiddleware;

//TODO add throttle and rate limiter

Route::post("/check-user", [CheckUserController::class, 'check'])->name('check-user');


Route::prefix('verification-code')->name('verification_code.')->group(function () {
    Route::post("send", [TokenController::class, 'send',])->name('send');
    Route::post("verify", [TokenController::class, 'verify',])->name('verify');
});


Route::middleware(['guest'])->group(function () {
    Route::post("/register", [RegisterUserController::class, 'store'])->name('register');
    Route::post("/login", [LoginController::class, 'login'])->name('login');
    Route::post("/forgot-password", [ForgotPasswordController::class, 'forgot'])->name('forgot-password');
});


Route::middleware(['auth:sanctum'])->group(function () {
    Route::middleware([EnsureContactsAreVerifiedMiddleware::class])->group(function () {
        Route::get("/who-am-i", [WhoAmIController::class, 'whoAmI'])->name('whoAmI');
        Route::get("/authorize", [WhoAmIController::class, 'authorize'])->name('authorize');
    });
});
    Route::post("/verify-contact", [VerifyContactController::class, 'verify'])->name('verify-contact');
