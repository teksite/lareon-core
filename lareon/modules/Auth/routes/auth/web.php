<?php


use Laravel\Fortify\RoutePath;
use Lareon\Modules\Auth\App\Http\Controllers\Ajax\Auth\VerificationCodeController;
use Lareon\Modules\Auth\App\Http\Controllers\Web\Auth\TwoFactorAuthenticatedSessionController;

Route::group(['middleware' => config('fortify.middleware', ['web'])], function () {
    $limiter = config('fortify.limiters.login');
    $twoFactorLimiter = config('fortify.limiters.two-factor');
    $verificationLimiter = config('fortify.limiters.verification', '6,1');


    Route::post(RoutePath::for('two-factor.login', '/two-factor-challenge'), [TwoFactorAuthenticatedSessionController::class, 'viaTOTP'])
         ->middleware(array_filter([
             'guest:' . config('fortify.guard'),
             $twoFactorLimiter ? 'throttle:' . $twoFactorLimiter : null,
         ]))->name('two-factor.login.store');

    Route::post('two-factor-recovery-challenge', [TwoFactorAuthenticatedSessionController::class, 'viaRecovery'])
         ->middleware(array_filter([
             'guest:' . config('fortify.guard'),
             $twoFactorLimiter ? 'throttle:' . $twoFactorLimiter : null,
         ]))->name('recovery.login.store');

    Route::prefix('two-factor-otp-challenge')->name('otp.')->group(function () {
        Route::post("send-otp", [VerificationCodeController::class, 'send',])->name('send')->middleware('throttle:2,1');
        Route::post("verify", [VerificationCodeController::class, 'verify',])->name('verify')->middleware('throttle:5,1');
    });
});
