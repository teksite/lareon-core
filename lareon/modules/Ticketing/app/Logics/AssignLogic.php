<?php

namespace Lareon\Modules\Ticketing\App\Logics;

use Lareon\Modules\Ticketing\App\Enums\AssignmentStatus;
use Lareon\Modules\Ticketing\App\Enums\AssignmentType;
use Lareon\Modules\Ticketing\App\Enums\ChangedByType;
use Lareon\Modules\Ticketing\App\Enums\SenderType;
use Lareon\Modules\Ticketing\App\Enums\TicketStatusType;
use Lareon\Modules\Ticketing\App\Events\TicketAssigned;
use Lareon\Modules\Ticketing\App\Events\TicketAssignmentResponded;
use Lareon\Modules\Ticketing\App\Exceptions\TicketAssignmentException;
use Lareon\Modules\Ticketing\App\Models\Ticket;
use Lareon\Modules\Ticketing\App\Models\TicketAssignment;
use Lareon\Steward\App\Models\Admin;
use Teksite\Handler\Facade\FetchData;
use Teksite\Handler\Services\ServiceWrapper;
use Throwable;

class AssignLogic
{
    public function __construct(public StatusLogLogic $statusLogger) {}


    /**
     * @throws Throwable
     */
    public function all(mixed $fetchData = [])
    {
        return ServiceWrapper::make(false)->do(function () {
            return FetchData::get(TicketAssignment::class, ['id', 'subject', 'ticket_number']);
        })->run();
    }

    /**
     * @throws Throwable
     */
    public function pendings($adminId, mixed $fetchData = [])
    {
        return ServiceWrapper::make(false)->do(function () use ($adminId) {
            return FetchData::get(TicketAssignment::query()
                                                  ->with('ticket', 'fromAdmin')
                                                  ->where('to_admin_id', $adminId)
                                                  ->where('status', AssignmentStatus::Pending), ['ticket']);
        })->run();
    }


    /**
     * @throws Throwable
     */
    public function claim(Ticket $ticket, int $adminId)
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
                $this->statusLogger->log($ticket, $from, TicketStatusType::InProgress, ChangedByType::Admin, $adminId, __('getting the ticket'));
            }

            event(new TicketAssigned($ticket, $assignment));

            return $assignment;
        })->run();
    }

    /**
     * @throws Throwable
     * @throws TicketAssignmentException
     */
    public function refer(Ticket $ticket, int $fromAdminId, int $toAdminId, string $reason)
    {
        if ($fromAdminId === $toAdminId) throw TicketAssignmentException::cannotReferToSelf();

        return ServiceWrapper::make(true)->do(function () use ($ticket, $fromAdminId, $toAdminId, $reason) {

            $ticket = Ticket::query()->whereKey($ticket->id)->lockForUpdate()->firstOrFail();
            $nextAdminId = Admin::query()->whereKey($toAdminId)->firstOrFail();

            if (!$ticket->isHeldBy($fromAdminId)) throw TicketAssignmentException::notHolder();

            $assignment = $ticket->assignments()->create([
                'type'          => AssignmentType::Referral,
                'from_admin_id' => $fromAdminId,
                'to_admin_id'   => $toAdminId,
                'reason'        => $reason,
                'status'        => AssignmentStatus::Pending,
            ]);


            $ticket->update(['last_activity_at' => now()]);
            $ticket->update([
                'holder_admin_id'  => null,
                'pending_admin_id' => $toAdminId,
            ]);

            $message = $ticket->appendMessage(
                SenderType::System,
                null,
                "the ticket has been forwarded to {$nextAdminId->name}({$toAdminId}): {$reason}",
                true,
            );

            event(new TicketAssigned($ticket, $assignment));

            return $assignment;

        })->run();
    }

    /**
     * @throws Throwable
     */
    public function accept(TicketAssignment $assignment, int $adminId)
    {
        return ServiceWrapper::make(true)->do(function () use ($assignment, $adminId) {

            $assignment = TicketAssignment::query()->whereKey($assignment->id)->lockForUpdate()->firstOrFail();
            $admin = Admin::query()->whereKey($adminId)->firstOrFail();

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

            $message = $ticket->appendMessage(
                SenderType::System,
                null,
                "the ticket is accepted by {$admin->name} ({$adminId})",
                true,
            );

            event(new TicketAssignmentResponded($ticket, $assignment));

            return $assignment;
        })->run();
    }

    /**
     * @throws Throwable
     * @throws TicketAssignmentException
     */
    public function reject(TicketAssignment $assignment, int $currentAdminId, string $decisionReason, int $nextAdminId)
    {
        if ($nextAdminId === $currentAdminId) throw TicketAssignmentException::cannotReferToSelf();

        return ServiceWrapper::make(true)->do(function () use ($assignment, $currentAdminId, $decisionReason, $nextAdminId) {

            $assignment = TicketAssignment::query()->whereKey($assignment->id)->lockForUpdate()->firstOrFail();
            $preAdminId = $assignment->from_admin_id;
            $nextAdmin = Admin::query()->whereKey($nextAdminId)->firstOrFail();

            $this->guardPendingAssignment($assignment, $currentAdminId);

            $assignment->update([
                'status'          => AssignmentStatus::Rejected,
                'decision_reason' => $decisionReason,
                'decided_at'      => now(),
            ]);

            $ticket = $assignment->ticket()->lockForUpdate()->first();

            $isReferredToPreAdmin = $nextAdminId === $preAdminId;

            $nextAssignment = $ticket->assignments()->create([
                'type'          => AssignmentType::Referral,
                'from_admin_id' => $currentAdminId,
                'to_admin_id'   => $nextAdminId,
                'reason'        => $decisionReason,
                'status'        => $isReferredToPreAdmin ? AssignmentStatus::Accepted : AssignmentStatus::Pending,
                'decided_at'    => now(),

            ]);




            if ($isReferredToPreAdmin) {
                $ticket->update([
                    'holder_admin_id'  => $preAdminId,
                    'pending_admin_id' => null,
                ]);
            } else {
                $ticket->update([
                    'holder_admin_id'  => null,
                    'pending_admin_id' => $nextAdminId,
                ]);
            }

            $message = $ticket->appendMessage(
                SenderType::System,
                null,
                "the ticket is rejected and has been forwarded to {$nextAdmin->name} ({$nextAdminId}): {$decisionReason}",
                true,
            );

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

