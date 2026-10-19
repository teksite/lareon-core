<?php

namespace Lareon\Modules\Ticketing\App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Lareon\Modules\Ticketing\App\Models\Ticket;
use Lareon\Modules\Ticketing\App\Models\TicketAssignment;


final class TicketAssigned
{
    use Dispatchable, SerializesModels;

    public function __construct(public readonly Ticket $ticket, public readonly TicketAssignment $assignment) {}
}
