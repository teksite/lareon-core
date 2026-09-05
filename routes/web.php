<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/{page:slug}', function () {
    return 'page in client';
})
     ->name('pages.show');
