<?php

use Illuminate\Support\Facades\Route;
use Lareon\Modules\Meta\App\Http\Controllers\Web\Admin\Templates\ElementsController;
use Lareon\Modules\Meta\App\Http\Controllers\Web\Admin\Templates\TemplatesController;

Route::name('visual.meta.')->prefix('visual')->group(function () {
    Route::resource('elements', ElementsController::class);
    Route::resource('templates', TemplatesController::class);
});
