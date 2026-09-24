<?php

use Illuminate\Support\Facades\Route;
use Lareon\Modules\Questionnaire\App\Http\Controllers\Ajax\Client\Inboxes\SubmitController;

Route::post('/client-submitting/form',[SubmitController::class ,'store'])->name('client.submitting.form')->middleware('throttle:5,1');
