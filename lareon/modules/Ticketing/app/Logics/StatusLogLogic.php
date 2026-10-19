<?php

namespace Lareon\Modules\Ticketing\App\Logics;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Lareon\Modules\Ticketing\App\Enums\ChangedByType;
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


class StatusLogLogic
{
    public function log(Ticket $ticket, ?TicketStatusType $from, TicketStatusType $to, ChangedByType $changedByType, ?int $changedById, ?string $note = null): void
    {
        $ticket->statusLogs()->create([
            'from_status'     => $from,
            'to_status'       => $to,
            'changed_by_type' => $changedByType,
            'changed_by_id'   => $changedById,
            'note'            => $note,
            'created_at'      => now(),
        ]);
    }
}

