<?php

namespace Lareon\Modules\Ticketing\App\Http\Controllers\Web\Panel\Tickets;

use Illuminate\Contracts\View\View;
use Lareon\Modules\Ticketing\App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TicketsController extends Controller
{
    public function __construct(private readonly TicketService $tickets)
    {
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
