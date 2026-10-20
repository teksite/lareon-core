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
use Teksite\Handler\Facade\Responder;
use Throwable;

class TicketAssignmentController extends Controller implements HasMiddleware
{


    public function __construct(public readonly AssignLogic $assign) {}

    public static function middleware(): array
    {
        return [
            new Middleware('can:admin.ticket.read'),
            new Middleware('can:admin.ticket.assign'),
            new Middleware('can:admin.ticket.read.all', only: ['all', 'read']),
            new Middleware('can:admin.ticket.create', only: ['create', 'store']),
            new Middleware('can:admin.ticket.edit', only: ['edit', 'update']),
            new Middleware('can:admin.ticket.delete', only: ['destroy']),
        ];
    }


    public function inbox(): View
    {
        $adminId = auth('admin')->id();
        $pending = TicketAssignment::query()
                                   ->with('ticket', 'fromAdmin')
                                   ->where('to_admin_id', $adminId)
                                   ->where('status', AssignmentStatus::Pending)
                                   ->latest('created_at')
                                   ->paginate((int)config('ticketing.pagination.tickets_per_page', 15));

        $adminModel = config('ticketing.admin_model');
        $admins = $adminModel::query()
                             ->where('id', '!=', $adminId)
                             ->orderBy('name')
                             ->get(['id', 'name']);

        return view('ticketing::admin.referrals.inbox', compact('pending', 'admins'));
    }


    public function create(Ticket $ticket): View
    {
        $adminId = auth('admin')->id();

        $adminModel = config('ticketing.admin_model');
        $admins = $adminModel::query()
                             ->where('id', '!=', $adminId)
                             ->orderBy('name')
                             ->get(['id', 'name']);

        return view('ticketing::admin.referrals.create', compact('ticket', 'admins'));
    }

    public function store(NewReferTicketRequest $request, Ticket $ticket): RedirectResponse
    {
        try {
            $this->assignments->refer(
                ticket     : $ticket,
                fromAdminId: $this->adminId(),
                toAdminId  : $request->integer('to_admin_id'),
                reason     : $request->string('reason')->toString(),
            );
        } catch (TicketAssignmentException $e) {
            return back()->withErrors(['to_admin_id' => $e->getMessage()]);
        }

        return redirect()
            ->route('ticketing.admin.tickets.index')
            ->with('status', 'تیکت با موفقیت ارجاع داده شد.');
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
