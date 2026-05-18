<?php

use Illuminate\Support\Facades\Route;
use Lareon\Modules\User\App\Http\Controllers\Web\Admin\Users\UsersController;


Route::resource('users', UsersController::class);
