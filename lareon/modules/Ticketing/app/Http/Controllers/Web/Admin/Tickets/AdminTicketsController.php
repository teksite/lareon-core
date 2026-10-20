<?php

namespace Lareon\Modules\Ticketing\App\Http\Controllers\Web\Admin\Tickets;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Ticketing\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Ticketing\App\Logics\AssignLogic;
use Lareon\Modules\Ticketing\App\Logics\TicketLogic;
use Lareon\Modules\Ticketing\App\Models\Ticket;
use Teksite\Handler\Facade\Responder;

class AdminTicketsController extends Controller implements HasMiddleware
{


    public function __construct(public readonly TicketLogic $logic, public readonly AssignLogic $assign) {}

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

    public function show(Ticket $ticket): View
    {
        $ticket->load(['user', 'messages', 'holderAdmin', 'messages.attachments', 'attachments', 'assignments.fromAdmin', 'assignments.toAdmin']);

        return view('ticketing::admin.pages.tickets.read', compact('ticket'));
    }

    public function create(): View
    {
        return view('ticketing::admin.pages.tickets.create');
    }

    public function store() {}

    /**
     * @throws \Throwable
     */
    public function destroy(Ticket $ticket)
    {
        $res = $this->logic->delete($ticket);
        return Responder::fromResult($res, success_url: route('admin.ticketing.tickets.index'));
    }

}
