<?php

use Lareon\Modules\Auth\App\Http\Controllers\Api\Auth\LoginController;
use Lareon\Modules\Auth\App\Http\Controllers\Api\Auth\RegisterUserController;
use Lareon\Modules\Auth\App\Http\Controllers\Api\Auth\TokenController;
use Lareon\Modules\Auth\App\Http\Controllers\Api\Auth\WhoAmIController;
use Lareon\Modules\Auth\App\Http\Middleware\EnsureContactsAreVerifiedMiddleware;

//TODO add throttle and rate limiter

Route::prefix('verification-code')->name('verification_code.')->group(function () {
    Route::post("send", [TokenController::class, 'send',])->name('send');
    Route::post("verify", [TokenController::class, 'verify',])->name('verify');
});


Route::middleware(['guest'])->group(function () {
    Route::post("/register", [RegisterUserController::class, 'store'])->name('register');
    Route::post("/login", [LoginController::class, 'login'])->name('login');
//    Route::post("/forgot-password", [ForgotPasswordController::class, 'forgot'])->name('forgot-password');
});



Route::middleware(['auth:sanctum'])->middleware([EnsureContactsAreVerifiedMiddleware::class])->group(function () {
    Route::get("/who-am-i", [WhoAmIController::class, 'whoAmI'])->name('whoAmI');
    Route::get("/authorize", [WhoAmIController::class, 'authorize'])->name('authorize');
//    Route::post("/verify-contact", [VerifyContactsController::class, 'verify'])->name('verify-contact');
});
