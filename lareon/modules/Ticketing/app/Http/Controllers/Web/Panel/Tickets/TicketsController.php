<?php

namespace Lareon\Modules\Ticketing\App\Http\Controllers\Web\Panel\Tickets;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Redirector;
use Lareon\Modules\Ticketing\App\Enums\TicketStatusType;
use Lareon\Modules\Ticketing\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Ticketing\App\Http\Requests\Panel\NewTicketRequest;
use Lareon\Modules\Ticketing\App\Logics\TicketLogic;
use Teksite\Handler\Facade\Responder;

class TicketsController extends Controller implements HasMiddleware
{


    public function __construct(public readonly TicketLogic $logic) {}

    public static function middleware(): array
    {
        return [
            new Middleware('can:panel.ticket.read'),
            new Middleware('can:panel.ticket.create', only: ['create', 'store']),
            new Middleware('can:panel.ticket.edit', only: ['edit', 'update']),
            new Middleware('can:panel.ticket.delete', only: ['destroy']),
        ];
    }

    /**
     * @throws \Throwable
     */
    public function index(): View {

        $statusInput= request()->get('status' , null);
        $status=TicketStatusType::tryFrom($statusInput) ?? null;
        $options= TicketStatusType::options();

        $tickets = $this->logic->allByUser(auth()->user() , $status )->result;
        return view('ticketing::panel.pages.tickets.index', compact('tickets' ,'options'));

    }

    public function create(): View {}

    /**
     * @throws \Throwable
     */
    public function store(NewTicketRequest $request): RedirectResponse|Redirector
    {
        $res = $this->logic->create($request->validated());

        if (!$res->success) return Responder::failed(trans('lareon::global.crud.error.created', ['attribute' => __('ticket')]))->go();

        dd($res->result);
        //Todo Add event event(new TicketCreated($ticket->result));

        return Responder::success(trans('lareon::global.crud.success.created', ['attribute' => __('ticket')]))->route('admin.tickets.index')->go();
    }

    public function show(Ticket $ticket): View {}

    public function reply(StoreUserReplyRequest $request, Ticket $ticket): RedirectResponse {}

    public function close(CloseTicketRequest $request, Ticket $ticket): RedirectResponse {}
}
