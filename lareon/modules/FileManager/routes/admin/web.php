<?php

use Illuminate\Support\Facades\Route;
use Lareon\Modules\FileManager\App\Http\Controllers\Web\Admin\Files\FileBrowserController;
use Lareon\Modules\FileManager\App\Http\Controllers\Web\Admin\Icons\IconsBrowserController;

Route::prefix('media')->name('media.')->group(function () {
    Route::get('/browser',[FileBrowserController::class ,'index'] )->name('browser.index');
    Route::get('/icons',[IconsBrowserController::class ,'index'] )->name('icons.index');

});
