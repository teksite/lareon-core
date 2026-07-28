<?php

use Illuminate\Support\Facades\Route;
use Lareon\Modules\Meta\App\Http\Controllers\Web\Admin\Templates\ElementsController;

Route::name('settings.meta.')->prefix('settings')->group(function () {
    Route::resource('elements', ElementsController::class);
});
