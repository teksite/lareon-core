<?php


//Route::trashResource('pages', PagesTrashController::class);
use Lareon\Modules\Page\App\Http\Controllers\Web\Admin\Pages\PagesController;

Route::resource('pages', PagesController::class);

