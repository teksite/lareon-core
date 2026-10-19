<?php

namespace Lareon\Modules\Ticketing\App\Logics;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Lareon\Modules\Ticketing\App\Enums\AssignmentStatus;
use Lareon\Modules\Ticketing\App\Enums\AssignmentType;
use Lareon\Modules\Ticketing\App\Enums\TicketStatusType;
use Lareon\Modules\Ticketing\App\Events\TicketAssigned;
use Lareon\Modules\Ticketing\App\Events\TicketAssignmentResponded;
use Lareon\Modules\Ticketing\App\Exceptions\TicketAssignmentException;
use Lareon\Modules\Ticketing\App\Models\Ticket;
use Lareon\Modules\Ticketing\App\Models\TicketAssignment;
use Teksite\Handler\Facade\FetchData;
use Teksite\Handler\Services\ServiceWrapper;
use Throwable;


class AssignLogic
{

    public function __construct(public StatusLogLogic $statusLogger) {}

    /**
     * @throws Throwable
     */
    public function claim(Ticket $ticket, int $adminId): TicketAssignment
    {
        return ServiceWrapper::make(true)->do(function () use ($ticket, $adminId) {
            $ticket = Ticket::query()->whereKey($ticket->id)->lockForUpdate()->firstOrFail();

            if (!$ticket->isUnassigned()) throw TicketAssignmentException::alreadyTaken();

            $assignment = $ticket->assignments()->create([
                'type'          => AssignmentType::Claim,
                'from_admin_id' => null,
                'to_admin_id'   => $adminId,
                'reason'        => null,
                'status'        => AssignmentStatus::Accepted,
                'decided_at'    => now(),
            ]);

            $ticket->update(['holder_admin_id' => $adminId]);

            if ($ticket->status === TicketStatusType::Opened) {
                $from = $ticket->status;
                $ticket->update(['status' => TicketStatusType::InProgress]);
                $this->statusLogger->log($ticket, $from, TicketStatusType::InProgress, 'admin', $adminId, 'در اختیار گرفتن تیکت');
            }

            event(new TicketAssigned($ticket, $assignment));

            return $assignment;
        })->run();
    }


    /**
     * @throws Throwable
     * @throws TicketAssignmentException
     */
    public function refer(Ticket $ticket, int $fromAdminId, int $toAdminId, string $reason): TicketAssignment
    {
        if ($fromAdminId === $toAdminId) throw TicketAssignmentException::cannotReferToSelf();

        return ServiceWrapper::make(true)->do(function () use ($ticket, $fromAdminId, $toAdminId, $reason) {

            $ticket = Ticket::query()->whereKey($ticket->id)->lockForUpdate()->firstOrFail();

            if (!$ticket->isHeldBy($fromAdminId)) throw TicketAssignmentException::notHolder();

            $assignment = $ticket->assignments()->create([
                'type'          => AssignmentType::Referral,
                'from_admin_id' => $fromAdminId,
                'to_admin_id'   => $toAdminId,
                'reason'        => $reason,
                'status'        => AssignmentStatus::Pending,
            ]);

            $ticket->update([
                'holder_admin_id'  => null,
                'pending_admin_id' => $toAdminId,
            ]);

            event(new TicketAssigned($ticket, $assignment));

            return $assignment;

        })->run();
    }

    /**
     * @throws Throwable
     */
    public function accept(TicketAssignment $assignment, int $adminId): TicketAssignment
    {
        return ServiceWrapper::make(true)->do(function () use ($assignment, $adminId) {

            $assignment = TicketAssignment::query()->whereKey($assignment->id)->lockForUpdate()->firstOrFail();

            $this->guardPendingAssignment($assignment, $adminId);

            $assignment->update([
                'status'     => AssignmentStatus::Accepted,
                'decided_at' => now(),
            ]);

            $ticket = $assignment->ticket()->lockForUpdate()->first();
            $ticket->update([
                'holder_admin_id'  => $adminId,
                'pending_admin_id' => null,
            ]);

            if ($ticket->status === TicketStatusType::Opened) $ticket->update(['status' => TicketStatusType::InProgress]);

            event(new TicketAssignmentResponded($ticket, $assignment));

            return $assignment;
        })->run();
    }


    /**
     * @throws Throwable
     * @throws TicketAssignmentException
     */
    public function reject(TicketAssignment $assignment, int $adminId, string $decisionReason, int $nextAdminId): TicketAssignment
    {
        if ($nextAdminId === $adminId) throw TicketAssignmentException::cannotReferToSelf();

        return ServiceWrapper::make(true)->do(function () use ($assignment, $adminId, $decisionReason, $nextAdminId) {

            $assignment = TicketAssignment::whereKey($assignment->id)->lockForUpdate()->firstOrFail();

            $this->guardPendingAssignment($assignment, $adminId);

            $assignment->update([
                'status'          => AssignmentStatus::Rejected,
                'decision_reason' => $decisionReason,
                'decided_at'      => now(),
            ]);

            $ticket = $assignment->ticket()->lockForUpdate()->first();

            $nextAssignment = $ticket->assignments()->create([
                'type'          => AssignmentType::Referral,
                'from_admin_id' => $adminId,
                'to_admin_id'   => $nextAdminId,
                'reason'        => $decisionReason,
                'status'        => AssignmentStatus::Pending,
            ]);

            $ticket->update([
                'holder_admin_id'  => null,
                'pending_admin_id' => $nextAdminId,
            ]);

            event(new TicketAssignmentResponded($ticket, $assignment));
            event(new TicketAssigned($ticket, $nextAssignment));

            return $assignment;
        })->run();
    }

    private function guardPendingAssignment(TicketAssignment $assignment, int $adminId): void
    {
        if ($assignment->to_admin_id !== $adminId) throw TicketAssignmentException::notAssignee();
        if (!$assignment->isPending()) throw TicketAssignmentException::alreadyDecided();
    }

}

