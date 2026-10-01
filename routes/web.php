<?php

use Illuminate\Support\Facades\Route;
use Lareon\Modules\Captcha\App\Rules\CaptchaRule;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/', function (\Illuminate\Http\Request $request) {
    $data= $request->validate([
        'captcha' => [new CaptchaRule('flat')],
    ]);
    dd($data);
});



Route::get('/{page:slug}', function () {
    dd(auth()->user()?->roles()->first()->title,auth('admin')->user()?->roles()->first()->title);
})
     ->name('pages.show');
