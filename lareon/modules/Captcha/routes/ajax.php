<?php

use Illuminate\Support\Facades\Route;
use Lareon\Modules\Captcha\App\Http\Controllers\Ajax\Client\Captcha\LocalCaptchaController;

Route::get('/client-submitting/captcha/load', [LocalCaptchaController::class, 'reload'])->name('client.captcha.load');
Route::get('/client-submitting/captcha/{config}', [LocalCaptchaController::class,'getCaptcha'])->name('client.captcha.get');
