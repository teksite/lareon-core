<?php

use Lareon\Modules\Questionnaire\App\Http\Controllers\Ajax\Admin\Analytics\AnalyticsController;

Route::prefix('questionnaire')->name('questionnaire.')->group(function () {
    Route::get('analytics', [AnalyticsController::class ,'get'])->name('analytics.get');
});

