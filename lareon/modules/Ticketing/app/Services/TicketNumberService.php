<?php

namespace Lareon\Modules\Ticketing\App\Services;

use Illuminate\Support\Str;
use Lareon\Modules\Ticketing\App\Models\Ticket;

class TicketNumberService {
    public function generateTicketNumber(): string
    {
        do {
            $candidate = 'TCK-'.now()->format('ymd').'-'.Str::upper(Str::random(5));
        } while (Ticket::withTrashed()->where('ticket_number', $candidate)->exists());
        return $candidate;
    }
}
