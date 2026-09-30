<?php

use Illuminate\Support\Facades\Route;
use Lareon\Modules\Captcha\App\Http\Controllers\Web\Admin\Captch\CaptchaController;

Route::get('settings/captcha', [CaptchaController::class, 'index'])->name('captcha.settings.read');
