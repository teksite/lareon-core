<?php

use Lareon\Modules\Page\App\Http\Controllers\Web\Admin\Pages\PagesController;
use Lareon\Modules\Page\App\Http\Controllers\Web\Admin\Pages\PagesTrashController;

Route::trashResource('pages', PagesTrashController::class);
Route::resource('pages', PagesController::class);

