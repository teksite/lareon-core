<?php

use Lareon\Modules\Auth\App\Http\Controllers\Api\Auth\CheckUserController;
use Lareon\Modules\Auth\App\Http\Controllers\Api\Auth\ForgetPasswordController;
use Lareon\Modules\Auth\App\Http\Controllers\Api\Auth\LoginController;
use Lareon\Modules\Auth\App\Http\Controllers\Api\Auth\RegisterUserController;
use Lareon\Modules\Auth\App\Http\Controllers\Api\Auth\TokenController;
use Lareon\Modules\Auth\App\Http\Controllers\Api\Auth\VerifyContactController;
use Lareon\Modules\Auth\App\Http\Controllers\Api\Auth\WhoAmIController;
use Lareon\Modules\Auth\App\Http\Middleware\EnsureContactsAreVerifiedMiddleware;

Route::post("/check-user", [CheckUserController::class, 'check'])->middleware('throttle:auth.check-user')->name('check-user');


Route::prefix('verification-code')->name('verification_code.')->group(function () {
    Route::post("send", [TokenController::class, 'send'])->middleware('throttle:auth.verification-send')->name('send');
    Route::post("verify", [TokenController::class, 'verify'])->middleware('throttle:auth.verification-verify')->name('verify');
});


Route::middleware(['guest'])->group(function () {
    Route::post("/register", [RegisterUserController::class, 'store'])->middleware('throttle:auth.register')->name('register');
    Route::post("/login", [LoginController::class, 'login'])->middleware('throttle:auth.login')->name('login');
    Route::post("/forgot-password", [ForgetPasswordController::class, 'reset'])->middleware('throttle:auth.forgot-password')->name('reset-password');

});


Route::middleware(['auth:sanctum'])->group(function () {
    Route::middleware([EnsureContactsAreVerifiedMiddleware::class, 'throttle:auth.session'])->group(function () {
        Route::get("/who-am-i", [WhoAmIController::class, 'whoAmI'])->name('whoAmI');
        Route::get("/authorize", [WhoAmIController::class, 'authorize'])->name('authorize');
    });
});
Route::post("/verify-contact", [VerifyContactController::class, 'verify'])->middleware('throttle:auth.verify-contact')->name('verify-contact');

