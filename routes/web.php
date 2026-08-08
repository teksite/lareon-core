<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
});
Route::get('/{page:slug}', function () {
})->name('pages.show');
