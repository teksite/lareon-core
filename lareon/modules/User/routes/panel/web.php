<?php

use Illuminate\Support\Facades\Route;
use Lareon\Modules\User\App\Http\Controllers\Web\Panel\Profile\PasskeyController;
use Lareon\Modules\User\App\Http\Controllers\Web\Panel\Profile\PasswordController;
use Lareon\Modules\User\App\Http\Controllers\Web\Panel\Profile\ProfileController;
use Lareon\Modules\User\App\Http\Controllers\Web\Panel\Profile\TwoFactorController;
use Lareon\Modules\User\App\Http\Controllers\Web\Panel\Users\UsersController;


Route::prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'edit'])->name('edit');
    Route::patch('/', [ProfileController::class, 'update'])->name('update');

    Route::get('/password', [PasswordController::class, 'edit'])->name('password');
    Route::patch('/password', [PasswordController::class, 'update'])->name('password.update');

    Route::get('/2fa', [TwoFactorController::class, 'edit'])->name('2fa');
    Route::patch('/2fa', [TwoFactorController::class, 'update'])->name('2fa.update');

    Route::prefix('passkeys')->name('passkeys.')->group(function () {
        Route::patch('{passkey}', [PasskeyController::class, 'update'])->name('update');
        Route::delete('{passkey}', [PasskeyController::class, 'destroy'])->name('destroy');
        Route::delete('/flush', [PasskeyController::class, 'flush'])->name('flush');
        Route::get('/', [PasskeyController::class, 'index'])->name('index');
    });
});

//Route::resource('users', UsersController::class);

