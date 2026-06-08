<?php

use Lareon\Modules\Auth\App\Http\Controllers\Api\Auth\TokenController;

//TODO add throttle and rate limiter

Route::prefix('verification-code')->name('verification_code.')->group(function () {
    Route::post("send", [TokenController::class, 'send',])->name('send');
    Route::post("verify", [TokenController::class, 'verify',])->name('verify');
});

