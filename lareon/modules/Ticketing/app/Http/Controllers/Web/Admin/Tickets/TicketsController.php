<?php

namespace Lareon\Modules\Ticketing\App\Http\Controllers\Web\Admin\Tickets;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Ticketing\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Ticketing\App\Logics\TicketLogic;
use Lareon\Modules\Ticketing\App\Models\Ticket;

class TicketsController extends Controller implements HasMiddleware
{


    public function __construct(public readonly TicketLogic $logic) {}

    public static function middleware(): array
    {
        return [
            new Middleware('can:admin.ticket.read'),
            new Middleware('can:admin.ticket.read.all', only: ['all', 'read']),
            new Middleware('can:admin.ticket.create', only: ['create', 'store']),
            new Middleware('can:admin.ticket.edit', only: ['edit', 'update']),
            new Middleware('can:admin.ticket.delete', only: ['destroy']),
        ];
    }

    /**
     * @throws \Throwable
     */
    public function index()
    {
        $tickets = $this->logic->all()->result;
        return view('ticketing::admin.pages.tickets.index', compact('tickets'));
    }

    public function read(Ticket $ticket): View
    {

        $adminId = $this->adminId();

        if ($ticket->isUnassigned()) {
            $this->assignments->claim($ticket, $adminId);
            $ticket->refresh();
        }

        $ticket->load(['user' ,'messages','holderAdmin','messages.attachments', 'attachments', 'assignments.fromAdmin', 'assignments.toAdmin']);

        return view('ticketing::admin.pages.tickets.read', compact('ticket'));
    }

    public function show(Ticket $ticket): View
    {
        $ticket=$ticket->load(['user' ,'messages','holderAdmin']);
        return view('ticketing::admin.pages.tickets.show', compact('ticket'));

    }

    public function create(): View
    {
        //        return view('ticketing::admin.pages.tickets.index');
    }

    public function store() {}

    public function destroy(Ticket $ticket): View {}

    public function reply(StoreAdminReplyRequest $request, Ticket $ticket): RedirectResponse {}

    public function close(CloseTicketRequest $request, Ticket $ticket): RedirectResponse {}


}
