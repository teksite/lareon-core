<?php

namespace Lareon\Modules\Ticketing\App\Logics;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Lareon\Modules\Ticketing\App\Enums\SenderType;
use Lareon\Modules\Ticketing\App\Enums\TicketStatusType;
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
    public function create(array $inputs = [], string $source = 'web' ,SenderType $senderType = SenderType::User)
    {
        return ServiceWrapper::make(true)->do(function () use ($senderType, $source, $inputs) {

            $inputs['ticket_number'] = (new TicketNumberService())->generateTicketNumber();
            $inputs['last_activity_at'] ??= now();
            $inputs['source'] = $source;
            $ticket = Ticket::query()->create(Arr::only($inputs, ['ticket_number', 'user_id', 'last_activity_at', 'subject', 'priority', 'source',]));

            $userId = $inputs['userId'] ?? auth()->id();
            $body = $inputs['body'];
            $message = $this->appendMessage($ticket, $senderType, $userId, $body);


            $files = $inputs['files'] ?? [];
            //  if ($inputs['files'] !== []) $this->attachments->storeMany($ticket, $message, 'user', $userId, $files);
            //  $this->statusLogger->log($ticket, null, TicketStatusType::Opened, 'user', $userId, 'create ticket');


            return $ticket->fresh(['messages', 'attachments']);
        })->run();

    }

    public function update(Model $model, array $inputs = []) {}

    public function delete(Model $model) {}


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

}

