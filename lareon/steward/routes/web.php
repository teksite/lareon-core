<?php

use Illuminate\Support\Facades\Route;
use Lareon\Steward\App\Http\Controllers\General\AttachmentsController;

Route::prefix('attachment-files')->name('attachment.files.')->group(function () {
    Route::get('load', [AttachmentsController::class, 'show'])->middleware('signed')->name('show');
});

