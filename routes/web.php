<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/admin/login', function () {
  auth('admin')->loginUsingId(1);
  return redirect()->to('/admin/check');
});
Route::get('/admin/check', function () {
    dd(auth('admin')->user()?->toArray());
});


Route::get('/{page:slug}', function () {
    dd(auth()->user()?->roles()->first()->title,auth('admin')->user()?->roles()->first()->title);
})
     ->name('pages.show');
