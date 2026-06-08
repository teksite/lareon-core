<?php

Route::prefix('auth')->name('auth.')->group(function () {
/*
    Route::post("/check-user", [CheckUserController::class, 'check'])->name('check-user');

    Route::prefix('verification-code')->name('verification_code.')->group(function () {
        Route::post("send", [VerificationCodeController::class, 'send',])->name('send')->middleware('throttle:check-user');
        Route::post("verify", [VerificationCodeController::class, 'verify',])->name('verify')->middleware('throttle:send-verification-code');
    });

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get("/who-am-i", [WhoAmIController::class, 'whoAmI'])->name('who-am-i')->middleware([EnsureContactsAreVerifiedMiddleware::class]);
        Route::post("/verify-contact", [VerifyContactsController::class, 'verify'])->name('verify-contact');
    });*/

    Route::middleware(['guest'])->group(function () {
        Route::post("/register", [\Lareon\Modules\Auth\App\Http\Controllers\Api\Auth\RegisteruserController::class, 'store'])->name('register');
//        Route::post("/login", [LoginController::class, 'login'])->name('login');
//        Route::post("/forgot-password", [ForgotPasswordController::class, 'forgot'])->name('forgot-password');
    });


});
