<?php

namespace Lareon\Modules\Ticketing\App\Http\Controllers\Web\Admin\Tickets;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Lareon\Modules\Ticketing\App\Enums\AssignmentStatus;
use Lareon\Modules\Ticketing\App\Exceptions\TicketAssignmentException;
use Lareon\Modules\Ticketing\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lareon\Modules\Ticketing\App\Http\Requests\Admin\AcceptReferTicketRequest;
use Lareon\Modules\Ticketing\App\Http\Requests\Admin\NewReferTicketRequest;
use Lareon\Modules\Ticketing\App\Http\Requests\Admin\RejectReferTicketRequest;
use Lareon\Modules\Ticketing\App\Logics\AssignLogic;
use Lareon\Modules\Ticketing\App\Models\Ticket;
use Lareon\Modules\Ticketing\App\Models\TicketAssignment;
use Lareon\Steward\App\Models\Admin;
use Teksite\Handler\Facade\Responder;
use Throwable;

class TicketAssignmentController extends Controller/* implements HasMiddleware*/
{


    public function __construct(public readonly AssignLogic $assign) {}

   /* public static function middleware(): array
    {
        return [
            new Middleware('can:admin.ticket.assign'),
            new Middleware('can:admin.ticket.assign.call' ,only: ['create' ,'store']),
            new Middleware('can:admin.ticket.assign.reject' ,only: ['reject']),
            new Middleware('can:admin.ticket.assign.accept' ,only: ['accept']),
        ];
    }*/


    /**
     * @throws Throwable
     */
    public function inbox(): View
    {
        $adminId = auth('admin')->id();
        $tickets = $this->assign->pendings($adminId)->result;

        return view('ticketing::admin.referrals.inbox', compact('tickets'));
    }


    public function create(Ticket $ticket): View
    {
        $adminId = auth('admin')->id();
        $admins = Admin::query()->where('id', '!=', $adminId)->orderBy('name')->get(['id', 'name']);
        return view('ticketing::admin.pages.referrals.create', compact('ticket', 'admins'));
    }

    /**
     * @throws Throwable
     * @throws TicketAssignmentException
     */
    public function store(NewReferTicketRequest $request, Ticket $ticket): RedirectResponse
    {
        $res = $this->assign->refer(
            ticket     : $ticket,
            fromAdminId: auth('admin')->id(),
            toAdminId  : $request->integer('to_admin_id'),
            reason     : $request->string('reason')->toString(),
        );

        return Responder::fromResult(
            $res,
            __('ticket successfully assigned'),
            __('failed to assign ticket. Please try again or notify the support team'),
            success_url: route('admin.tickets.index')
        );

    }

    /**
     * @throws Throwable
     */
    public function accept(AcceptReferTicketRequest $request, TicketAssignment $assignment): RedirectResponse
    {
        $adminId = auth('admin')->id();

        $res = $this->assign->accept($assignment, $adminId);

        return Responder::fromResult(
            $res,
            __('the referral has been accepted, and the ticket is now assigned to you'),
            success_url: route('admin.tickets.show', $assignment->ticket_id)
        );
    }

    /**
     * @throws TicketAssignmentException
     * @throws Throwable
     */
    public function reject(RejectReferTicketRequest $request, TicketAssignment $assignment): RedirectResponse
    {
        $adminId = auth('admin')->id();
        $res = $this->assign->reject($assignment, $adminId, $request->string('decision_reason')->toString(), $request->integer('next_admin_id'));

        return Responder::fromResult(
            $res,
            __('the referral was rejected, and the ticket was assigned to another admin'),
            success_url: route('tickets.referrals.inbox', $assignment->ticket_id)
        );
    }


}
