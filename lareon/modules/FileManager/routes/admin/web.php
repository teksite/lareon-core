<?php

use Illuminate\Support\Facades\Route;
use Lareon\Modules\FileManager\App\Http\Controllers\Web\Admin\FileBrowser\FileBrowserController;

Route::get('/browser',[FileBrowserController::class ,'index'] )->name('browser.index');
