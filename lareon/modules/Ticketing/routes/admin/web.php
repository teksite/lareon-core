<?php

use Illuminate\Support\Facades\Route;
use Lareon\Modules\Ticketing\App\Http\Controllers\Web\Admin\Tickets\TicketsController;


Route::prefix('tickets')->name('tickets.')->group(function (): void {
    Route::get('/', [TicketsController::class, 'index'])->name('index');
    Route::get('/', [TicketsController::class, 'create'])->name('create');
    Route::get('/{ticket}', [TicketsController::class, 'show'])->middleware(EnsureAdminCanViewTicket::class)->name('show');
    Route::post('/{ticket}/reply', [TicketsController::class, 'reply'])->middleware(EnsureAdminOwnsTicket::class)->name('reply');
    Route::post('/{ticket}/close', [TicketsController::class, 'close'])->middleware(EnsureAdminOwnsTicket::class)->name('close');
//    Route::get('/{ticket}/refer', [TicketsController::class, 'create'])->middleware(EnsureAdminOwnsTicket::class)->name('refer.create');
//    Route::post('/{ticket}/refer', [TicketsController::class, 'store'])->middleware(EnsureAdminOwnsTicket::class)->name('refer.store');
});
//
//Route::prefix('admin/ticket-referrals')
//     ->middleware('auth:'.config('ticketing.guards.admin', 'admin'))
//     ->name('ticketing.admin.referrals.')
//     ->group(function (): void {
//         Route::get('/', [TicketAssignmentController::class, 'inbox'])->name('inbox');
//         Route::post('/{assignment}/accept', [TicketAssignmentController::class, 'accept'])->name('accept');
//         Route::post('/{assignment}/reject', [TicketAssignmentController::class, 'reject'])->name('reject');
//     });
