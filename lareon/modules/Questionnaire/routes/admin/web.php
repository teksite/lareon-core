<?php

use Illuminate\Support\Facades\Route;
use Lareon\Modules\Questionnaire\App\Http\Controllers\Web\Admin\Analytics\AnalyticsController;
use Lareon\Modules\Questionnaire\App\Http\Controllers\Web\Admin\Forms\FormsController;
use Lareon\Modules\Questionnaire\App\Http\Controllers\Web\Admin\Forms\TrashFormsController;
use Lareon\Modules\Questionnaire\App\Http\Controllers\Web\Admin\Inboxes\ExportController;
use Lareon\Modules\Questionnaire\App\Http\Controllers\Web\Admin\Inboxes\InboxesController;
use Lareon\Modules\Questionnaire\App\Http\Controllers\Web\Admin\Inboxes\TrashInboxesController;

Route::prefix('questionnaire')->name('questionnaire.')->group(function () {
    Route::trashResource('inboxes', TrashInboxesController::class);
    Route::resource('inboxes', InboxesController::class);

    Route::trashResource('forms', TrashFormsController::class);
    Route::resource('forms', FormsController::class);


    Route::get('analytics', [AnalyticsController::class, 'show'])->name('analytics.show');

    Route::prefix('export')->name('inboxes.export.')->group(function () {
        Route::get('/execute', [ExportController::class, 'export'])->name('execute');
        Route::get('/', [ExportController::class, 'index'])->name('index');
    });
});
