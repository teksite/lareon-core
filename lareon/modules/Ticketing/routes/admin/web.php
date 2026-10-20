<?php

use Illuminate\Support\Facades\Route;
use Lareon\Modules\Ticketing\App\Http\Controllers\Web\Admin\Tickets\AdminTicketsController;
use Lareon\Modules\Ticketing\App\Http\Controllers\Web\Admin\Tickets\TicketAssignmentController;
use Lareon\Modules\Ticketing\App\Http\Controllers\Web\Admin\Tickets\TicketsController;
use Lareon\Modules\Ticketing\App\Http\Middleware\EnsureAdminCanViewTicket;
use Lareon\Modules\Ticketing\App\Http\Middleware\EnsureAdminOwnsTicket;



Route::prefix('ticketing')->name('ticketing.tickets.')->group(function (): void {
    Route::get('/', [AdminTicketsController::class, 'index'])->name('index');
    Route::get('/{ticket}', [AdminTicketsController::class, 'show'])->name('show');
    Route::get('/create', [AdminTicketsController::class, 'create'])->name('create');
    Route::post('/create', [AdminTicketsController::class, 'store'])->name('store');
    Route::delete('/{ticket}', [AdminTicketsController::class, 'destroy'])->name('destroy');
});

Route::prefix('tickets')->name('tickets.')->group(function (): void {
    Route::get('/', [TicketsController::class, 'index'])->name('index');

    Route::get('/{ticket}', [TicketsController::class, 'show'])->middleware(EnsureAdminCanViewTicket::class)->name('show');

    Route::post('/{ticket}/reply', [TicketsController::class, 'reply'])->middleware(EnsureAdminOwnsTicket::class)->name('reply');
    Route::post('/{ticket}/close', [TicketsController::class, 'close'])->middleware(EnsureAdminOwnsTicket::class)->name('close');

    Route::get('/{ticket}/refer', [TicketAssignmentController::class, 'create'])->middleware(EnsureAdminOwnsTicket::class)->name('refer.create');
    Route::post('/{ticket}/refer', [TicketAssignmentController::class, 'store'])->middleware(EnsureAdminOwnsTicket::class)->name('refer.store');
});


Route::prefix('tickets/referrals')->name('tickets.referrals.')->group(function (): void {})->group(function (): void {
    Route::get('/', [TicketAssignmentController::class, 'inbox'])->name('inbox');
    Route::post('/{$ticket}/accept', [TicketAssignmentController::class, 'accept'])->name('accept');
    Route::post('/{$ticket}/reject', [TicketAssignmentController::class, 'reject'])->name('reject');
});
