<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {});
Route::get('/{page:slug}', function () {
    return 'page in client';
})
     ->name('pages.show');
