<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/{page:slug}', function () {
    dd(auth()->user()?->roles()->first()->title,auth('admin')->user()?->roles()->first()->title);
})
     ->name('pages.show');
