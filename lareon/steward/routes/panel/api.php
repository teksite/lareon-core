<?php


Route::get('/get-menu', [\Lareon\Steward\App\Http\Controllers\Web\Panel\General\GetMenuController::class, 'get'])->name('get');
