<?php

use Illuminate\Support\Facades\Route;
use Lareon\Modules\Captcha\App\Http\Controllers\Ajax\Client\Captcha\LocalCaptchaController;
use Lareon\Modules\Captcha\App\Services\CaptchaService;

Route::prefix('client-submitting/captcha')->name('client.captcha.')->group(function () {

Route::get('load', [LocalCaptchaController::class, 'reload'])->middleware('throttle:captcha')->name('load');

Route::get('{token}', [LocalCaptchaController::class, 'image'])->where('token', trim(CaptchaService::TOKEN_PATTERN, '/^$'))->name('image');
});
