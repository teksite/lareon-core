<?php


use Illuminate\Support\Facades\Route;
use Lareon\Modules\Meta\App\Http\Controllers\Ajax\Admin\Models\ModelsLoaderController;

Route::post('/models/search', [ModelsLoaderController::class, 'load'])->name('models.search.load');
