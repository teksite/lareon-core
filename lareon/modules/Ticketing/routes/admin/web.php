<?php

use Illuminate\Support\Facades\Route;
use Lareon\Modules\Ticketing\App\Http\Controllers\Web\Admin\Tickets\TicketAssignmentController;
use Lareon\Modules\Ticketing\App\Http\Controllers\Web\Admin\Tickets\TicketsController;
use Lareon\Modules\Ticketing\App\Http\Middleware\EnsureAdminCanViewTicket;
use Lareon\Modules\Ticketing\App\Http\Middleware\EnsureAdminOwnsTicket;


Route::prefix('tickets')->name('tickets.')->group(function (): void {
    Route::get('/', [TicketsController::class, 'index'])->name('index');
    Route::get('/{ticket}/read', [TicketsController::class, 'read'])->name('read');

    Route::get('/create', [TicketsController::class, 'create'])->name('create');
    Route::post('/create', [TicketsController::class, 'store'])->name('store');
    Route::delete('/{ticket}', [TicketsController::class, 'destroy'])->name('destroy');


    Route::get('/{ticket}', [TicketsController::class, 'show'])->middleware(EnsureAdminCanViewTicket::class)->name('show');

    Route::post('/{ticket}/reply', [TicketsController::class, 'reply'])->middleware(EnsureAdminOwnsTicket::class)->name('reply');
    Route::post('/{ticket}/close', [TicketsController::class, 'close'])->middleware(EnsureAdminOwnsTicket::class)->name('close');

    Route::get('/{ticket}/refer', [TicketAssignmentController::class, 'create'])->middleware(EnsureAdminOwnsTicket::class)->name('refer.create');
    Route::post('/{ticket}/refer', [TicketAssignmentController::class, 'store'])->middleware(EnsureAdminOwnsTicket::class)->name('refer.store');
});

Route::prefix('tickets/referrals')->name('tickets.referrals.')->group(function (): void {})->group(function (): void {
    Route::get('/', [TicketAssignmentController::class, 'inbox'])->name('inbox');
    Route::post('/{assignment}/accept', [TicketAssignmentController::class, 'accept'])->name('accept');
    Route::post('/{assignment}/reject', [TicketAssignmentController::class, 'reject'])->name('reject');
});
