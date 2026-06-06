<?php


use Laravel\Fortify\RoutePath;
use Lareon\Modules\Auth\App\Http\Controllers\Web\Auth\TwoFactorAuthenticatedSessionController;

Route::group(['middleware' => config('fortify.middleware', ['web'])], function () {
    $limiter = config('fortify.limiters.login');
    $twoFactorLimiter = config('fortify.limiters.two-factor');
    $verificationLimiter = config('fortify.limiters.verification', '6,1');


//    Route::post(RoutePath::for('login', '/login'), [AuthenticatedSessionController::class, 'store'])
//        ->middleware(array_filter([
//            'guest:' . config('fortify.guard'),
//            $limiter ? 'throttle:' . $limiter : null,
//        ]))->name('login.store');

    Route::post(RoutePath::for('two-factor.login', '/two-factor-challenge'), [TwoFactorAuthenticatedSessionController::class, 'viaOTP'])
         ->middleware(array_filter([
             'guest:' . config('fortify.guard'),
             $twoFactorLimiter ? 'throttle:' . $twoFactorLimiter : null,
         ]))->name('two-factor.login.store');

    Route::post('two-factor-recovery-challenge', [TwoFactorAuthenticatedSessionController::class, 'viaRecovery'])
         ->middleware(array_filter([
             'guest:' . config('fortify.guard'),
             $twoFactorLimiter ? 'throttle:' . $twoFactorLimiter : null,
         ]))->name('recovery.login.store');

    Route::post('otp-challenge', [TwoFactorAuthenticatedSessionController::class, 'viaRecovery'])
         ->middleware(array_filter([
             'guest:' . config('fortify.guard'),
             $twoFactorLimiter ? 'throttle:' . $twoFactorLimiter : null,
         ]))->name('otp.login.store');
});
