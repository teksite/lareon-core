<?php

namespace Lareon\Modules\Ticketing\App\Http\Controllers\Web\Admin\Tickets;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Ticketing\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Ticketing\App\Http\Requests\Admin\CloseTicketRequest;
use Lareon\Modules\Ticketing\App\Http\Requests\Admin\ReplyTicketRequest;
use Lareon\Modules\Ticketing\App\Logics\AssignLogic;
use Lareon\Modules\Ticketing\App\Logics\TicketLogic;
use Lareon\Modules\Ticketing\App\Models\Ticket;
use Lareon\Steward\App\Models\Admin;
use Teksite\Handler\Facade\Responder;

class TicketsController extends Controller implements HasMiddleware
{


    public function __construct(public readonly TicketLogic $logic, public readonly AssignLogic $assign) {}

    public static function middleware(): array
    {
        return [
            new Middleware('can:admin.ticket.read'),
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
        $filters = ['all' => 'all', 'unassigned' => 'unassigned', 'open' => 'opened', 'own' => 'owned',];

        $admin = auth()->guard('admin')->user();
        $filterQuery = request()->input('type');

        $tickets = match ($filterQuery) {
            'unassigned' => $this->logic->unassigns()->result,
            'own'        => $this->logic->heldBy($admin)->result,
            'open'       => $this->logic->openAndHeldByAdmin($admin)->result,
            default      => $this->logic->visibleToAdmin($admin)->result,
        };

        return view('ticketing::admin.pages.tickets.index', compact('tickets', 'filters', 'filterQuery'));
    }


    /**
     * @throws \Throwable
     */
    public function show(Ticket $ticket): View
    {
        $adminId = auth('admin')->id();

        if ($ticket->isUnassigned() && !$ticket->isClosed()) {
            $this->assign->claim($ticket, $adminId);
            $ticket->refresh();
        }

        $ticket->load(['user', 'messages', 'holderAdmin', 'messages.attachments', 'attachments', 'assignments.fromAdmin', 'assignments.toAdmin']);

        return view('ticketing::admin.pages.tickets.show', [
            'ticket'            => $ticket,
            'isHolder'          => $ticket->isHeldBy($adminId),
            'isPendingDecision' => $ticket->isPendingFor($adminId),
        ]);
    }


    public function store()
    {

    }

    public function create()
    {

    }
    /**
     * @throws \Throwable
     */
    public function reply(ReplyTicketRequest $request, Ticket $ticket): RedirectResponse
    {
        $data = $request->validated();
        $res = $this->logic->replyAsAdmin($ticket, auth('admin')->id(), $data['body'], $data['attachment'] ?? [], isset($data['is_internal_note']));
        return Responder::fromResult($res, trans('your reply is submitted'))->go();

    }

    /**
     * @throws \Throwable
     */
    public function close(CloseTicketRequest $request, Ticket $ticket): RedirectResponse
    {
        $res = $this->logic->closeByAdmin($ticket, auth('admin')->id(), $request->string('note', 'close by admin')->toString());
        return Responder::fromResult($res, trans('the ticket #:number is closed', ['number' => $ticket->ticket_number]))->go();
    }


}
