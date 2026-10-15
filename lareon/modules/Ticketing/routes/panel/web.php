<?php

use Illuminate\Support\Facades\Route;
use Lareon\Modules\Ticketing\App\Http\Controllers\Web\Panel\Tickets\TicketsController;

Route::prefix('tickets')->name('tickets.')
     ->group(function (): void {
         Route::get('/', [TicketsController::class, 'index'])->name('index');
         Route::get('/create', [TicketsController::class, 'create'])->name('create');
         Route::post('/', [TicketsController::class, 'store'])->name('store');
         Route::get('/{ticket}', [TicketsController::class, 'show'])->name('show');
         Route::post('/{ticket}/reply', [TicketsController::class, 'reply'])->name('reply');
         Route::post('/{ticket}/close', [TicketsController::class, 'close'])->name('close');
     });
