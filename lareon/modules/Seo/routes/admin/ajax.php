<?php

use Illuminate\Support\Facades\Route;
use Lareon\Modules\Seo\App\Http\Controllers\Ajax\Admin\Seo\SchemaLoaderController;

Route::prefix('seo')->name('seo.')->group(function () {
    Route::post('schema/loader',[SchemaLoaderController::class , 'get'])->name('schema.loader');
});
