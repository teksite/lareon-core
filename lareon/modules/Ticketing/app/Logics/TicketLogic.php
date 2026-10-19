<?php

namespace Lareon\Modules\Ticketing\App\Logics;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Lareon\Modules\Ticketing\App\Enums\CloseByType;
use Lareon\Modules\Ticketing\App\Enums\SenderType;
use Lareon\Modules\Ticketing\App\Enums\TicketStatusType;
use Lareon\Modules\Ticketing\App\Events\TicketClosed;
use Lareon\Modules\Ticketing\App\Events\TicketReplyEvent;
use Lareon\Modules\Ticketing\App\Models\Ticket;
use Lareon\Modules\Ticketing\App\Models\TicketMessage;
use Lareon\Modules\Ticketing\App\Services\TicketNumberService;
use Lareon\Modules\User\App\Models\User;
use Teksite\Handler\Facade\FetchData;
use Teksite\Handler\Services\ServiceWrapper;
use Throwable;


class TicketLogic
{

    /**
     * @throws Throwable
     */
    public function all(mixed $fetchData = [])
    {
        return ServiceWrapper::make(false)->do(function () {
            return FetchData::get(Ticket::class);
        })->run();
    }

    /**
     * @throws Throwable
     */
    public function allByUser(User|null $user = null, null|TicketStatusType $status = null): mixed
    {
        $user ??= auth()->user();
        return ServiceWrapper::make(false)->do(function () use ($user, $status) {
            return FetchData::get(model: fn() => $user->tickets()->when(
                $status instanceof TicketStatusType,
                fn($query) => $query->where('status', $status->value))
            );
        })->run();
    }

    public function first(array $inputs = []) {}

    /**
     * @throws Throwable
     */
    public function create(array $inputs = [], string $source = 'web', SenderType $senderType = SenderType::User, $senderId = null)
    {
        return ServiceWrapper::make(true)->do(function () use ($senderId, $senderType, $source, $inputs) {

            $ticket = Ticket::query()->create([
                'ticket_number'    => (new TicketNumberService())->generateTicketNumber(),
                'user_id'          => $senderId,
                'last_activity_at' => now(),
                'subject'          => $inputs['subject'],
                'priority'         => $inputs['priority'],
                'source'           => $source,
            ]);


            $message = $this->appendMessage($ticket, $senderType, $senderId, $inputs['body']);


            $files = $inputs['files'] ?? [];
            //TODO add attachment and Logger
            //  if ($inputs['files'] !== []) $this->attachments->storeMany($ticket, $message, 'user', $userId, $files);
            //  $this->statusLogger->log($ticket, null, TicketStatusType::Opened, 'user', $userId, 'create ticket');


            return $ticket->fresh(['messages', 'attachments']);
        })->run();

    }

    public function update(Ticket $ticket, array $inputs = []) {}

    public function delete(Ticket $ticket) {}

    /**
     * @throws Throwable
     */
    public function closeByUser(Ticket $ticket, int $userId, ?string $note = null)
    {
        return $this->close($ticket, CloseByType::User, $userId, $note);
    }

    /**
     * @throws Throwable
     */
    public function closeByAdmin(Ticket $ticket, int $adminId, ?string $note = null)
    {
        return $this->close($ticket, CloseByType::Admin, $adminId, $note);
    }

    /**
     * @throws Throwable
     */
    private function close(Ticket $ticket, CloseByType $closedByType, int $closedById, ?string $note)
    {
        return ServiceWrapper::make(true)->do(function () use ($ticket, $closedByType, $closedById, $note) {
            $ticket->update([
                'status'           => TicketStatusType::Closed,
                'closed_by_type'   => $closedByType,
                'closed_by_id'     => $closedById,
                'closed_at'        => now(),
                'last_activity_at' => now(),
            ]);
            //TODO Add Logger $this->statusLogger->log($ticket, null, TicketStatusType::Closed, $closedByType, $closedById, $note ?? 'بسته شدن تیکت');

            if ($note !== null && $note !== '') {
                $senderType = $closedByType === 'user' ? SenderType::User : SenderType::Admin;
                $this->appendMessage($ticket, $senderType, $closedById, $note);
            }

            event(new TicketClosed($ticket));

            return $ticket->fresh();
        })->run();
    }


    public function replyAsUser(Ticket $ticket, int $userId, string $body, array $files = [])
    {
        return ServiceWrapper::make(true)->do(function () use ($ticket, $userId, $body, $files) {
            $message = $this->appendMessage($ticket, SenderType::User, $userId, $body);

           //Todo if ($files !== []) $this->attachments->storeMany($ticket, $message, 'user', $userId, $files);

            $this->transitionStatus($ticket, TicketStatusType::WaitingAdmin, 'user', $userId);

            event(new TicketReplyEvent($ticket, $message));
            return $message;
        })->run();
    }


    public function replyAsAdmin(Ticket $ticket, int $adminId, string $body, array $files = [], bool $internalNote = false)
    {
        return ServiceWrapper::make(true)->do(function () use ($adminId, $ticket, $internalNote, $body, $files) {
            $message = $this->appendMessage($ticket, SenderType::Admin, $adminId, $body, $internalNote);

           //Todo if ($files !== []) $this->attachments->storeMany($ticket, $message, 'admin', $adminId, $files);

            if (!$internalNote) $this->transitionStatus($ticket, TicketStatusType::Answered, 'admin', $adminId);

            event(new TicketReplyEvent($ticket, $message));

            return $message;
        })->run();
    }


    private function appendMessage(Ticket $ticket, SenderType $senderType, ?int $senderId, string $body, bool $internalNote = false): TicketMessage
    {
        $message = $ticket->messages()->create([
            'sender_type'      => $senderType->value,
            'sender_id'        => $senderId,
            'body'             => $body,
            'is_internal_note' => $internalNote,
        ]);

        $ticket->update(['last_activity_at' => now()]);

        return $message;
    }

    private function transitionStatus(Ticket $ticket, TicketStatusType $to, string $byType, ?int $byId): void
    {
        $from = $ticket->status;
        $ticket->update(['status' => $to]);
        //Todo        $this->statusLogger->log($ticket, $from, $to, $byType, $byId);
    }


}

