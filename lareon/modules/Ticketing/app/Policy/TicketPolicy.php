<?php

namespace Lareon\Modules\Ticketing\App\Policy;

use Lareon\Modules\Ticketing\App\Models\Ticket;
use \Lareon\Modules\User\App\Models\User;

class TicketPolicy
{
    /**
     * Create a new policy instance.
     */

    public function reply(mixed $user, Ticket $ticket): bool

    {
        return $ticket->isOwnedBy((int)$user->getAuthIdentifier()) && !$ticket->isClosed();
    }

    public function close(mixed $user, Ticket $ticket): bool
    {
        return $ticket->isOwnedBy((int)$user->getAuthIdentifier()) && !$ticket->isClosed();
    }
}
