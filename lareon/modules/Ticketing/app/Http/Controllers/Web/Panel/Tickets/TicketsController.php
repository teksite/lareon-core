<?php

namespace Lareon\Modules\Ticketing\App\Http\Controllers\Web\Panel\Tickets;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Gate;
use Lareon\Modules\Ticketing\App\Enums\PriorityType;
use Lareon\Modules\Ticketing\App\Enums\SenderType;
use Lareon\Modules\Ticketing\App\Enums\TicketStatusType;
use Lareon\Modules\Ticketing\App\Events\TicketCreated;
use Lareon\Modules\Ticketing\App\Http\Controllers\Controller;
use Lareon\Modules\Ticketing\App\Http\Requests\Admin\CloseTicketRequest;
use Lareon\Modules\Ticketing\App\Http\Requests\Panel\NewTicketRequest;
use Lareon\Modules\Ticketing\App\Http\Requests\Panel\ReplyTicketMessageRequest;
use Lareon\Modules\Ticketing\App\Logics\TicketLogic;
use Lareon\Modules\Ticketing\App\Models\Ticket;
use Teksite\Handler\Facade\Responder;

class TicketsController extends Controller implements HasMiddleware
{


    public function __construct(public readonly TicketLogic $logic) {}

    public static function middleware(): array
    {
        return [
            new Middleware('can:panel.ticket.read'),
            new Middleware('can:panel.ticket.create', only: ['create', 'store']),
            new Middleware('can:panel.ticket.reply', only: ['reply']),
            new Middleware('can:panel.ticket.close', only: ['close']),
            new Middleware('can:panel.ticket.delete', only: ['destroy']),
        ];
    }

    /**
     * @throws \Throwable
     */
    public function index(): View
    {

        $statusInput = request()->input('status');
        $status = $statusInput ? TicketStatusType::tryFrom($statusInput) : null;
        $options = TicketStatusType::options();

        $tickets = $this->logic->allByUser(auth()->user(), $status)->result;

        return view('ticketing::panel.pages.tickets.index', compact('tickets', 'options'));

    }

    public function create(): View
    {

        $ticket = new Ticket();
        $priorities = PriorityType::cases();
        return view('ticketing::panel.pages.tickets.create', compact('priorities', 'ticket'));

    }

    /**
     * @throws \Throwable
     */
    public function store(NewTicketRequest $request): RedirectResponse|Redirector
    {
        $res = $this->logic->create($request->validated(), 'web', SenderType::User, auth()->id());

        if (!$res->success) return Responder::failed(trans('lareon::global.crud.error.created', ['attribute' => __('ticket')]))->go();

        event(new TicketCreated($res->result));

        return Responder::success(trans('lareon::global.crud.success.created', ['attribute' => __('ticket')]))->route('panel.tickets.show', ['ticket' => $res->result])->go();
    }

    public function show(Ticket $ticket): View
    {

        Gate::denyIf($ticket->user_id !== auth()->id());
        $user = auth('web')->user();

        $ticket->load(['user', 'messages', 'messages.attachments', 'attachments']);
        return view('ticketing::panel.pages.tickets.show', compact('ticket', 'user'));
    }

    public function reply(ReplyTicketMessageRequest $request, Ticket $ticket): RedirectResponse
    {

        $data = $request->validated();
        $res = $this->logic->replyAsUser($ticket, auth()->id(), $data['body'], $data['file'] ?? []);
        return Responder::fromResult($res, trans('your message is submitted'))->go();
    }

    /**
     * @throws \Throwable
     */
    public function close( CloseTicketRequest $request ,Ticket $ticket)
    {
        $res = $this->logic->closeByUser($ticket, auth()->id() ,'close by user');
        return Responder::fromResult($res, trans('the ticket #:number is closed', ['number' => $ticket->ticket_number]))->go();

    }
}
