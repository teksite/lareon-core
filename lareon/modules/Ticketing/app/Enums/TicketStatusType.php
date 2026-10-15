<?php

namespace Lareon\Modules\Ticketing\App\Enums;

enum TicketStatusType: int
{
    case Closed = 0;
    case Opened  = 1;


    public function label(): string
    {
        return match ($this) {
            self::Closed => __('close'),
            self::Opened  => __('open'),
        };
    }

}
