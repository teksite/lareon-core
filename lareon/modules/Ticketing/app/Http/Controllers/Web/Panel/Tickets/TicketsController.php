<?php

namespace Lareon\Modules\Ticketing\App\Http\Controllers\Web\Panel\Tickets;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Ticketing\App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TicketsController extends Controller implements HasMiddleware
{


    public function __construct(private readonly TicketService $tickets)
    {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:admin.ticket.read'),
            new Middleware('can:admin.ticket.create', only: ['create', 'store']),
            new Middleware('can:admin.ticket.edit', only: ['edit', 'update']),
            new Middleware('can:admin.ticket.delete', only: ['destroy']),
        ];
    }

    public function index(): View
    {
    }

    public function create(): View
    {

    }

    public function store(StoreTicketRequest $request): RedirectResponse
    {

    }

    public function show(Ticket $ticket): View
    {

    }

    public function reply(StoreUserReplyRequest $request, Ticket $ticket): RedirectResponse
    {

    }

    public function close(CloseTicketRequest $request, Ticket $ticket): RedirectResponse
    {

    }
}
