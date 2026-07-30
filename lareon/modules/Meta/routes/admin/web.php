<?php

use Illuminate\Support\Facades\Route;
use Lareon\Modules\Meta\App\Http\Controllers\Web\Admin\Templates\ElementsController;
use Lareon\Modules\Meta\App\Http\Controllers\Web\Admin\Templates\TemplatesController;

Route::name('settings.meta.')->prefix('settings')->group(function () {
    Route::resource('elements', ElementsController::class);
    Route::resource('template', TemplatesController::class);
});
