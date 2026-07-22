<?php

use Illuminate\Support\Facades\Route;
use Lareon\Modules\FileManager\App\Http\Controllers\Web\Admin\FileBrowser\FileBrowserController;

Route::prefix('filemanager')->name('filemanager.')->group(function () {
    Route::get('/browser',[FileBrowserController::class ,'index'] )->name('browser.index');

});
