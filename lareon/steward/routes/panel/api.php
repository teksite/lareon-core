<?php


use Lareon\Steward\App\Http\Controllers\Web\Panel\General\GetMenuController;

Route::get('/get-menu', [GetMenuController::class, 'get'])->name('get');
