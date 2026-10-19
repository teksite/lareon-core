<?php

namespace Lareon\Modules\Ticketing\App\Enums;

enum AssignmentStatus: int
{
    case Pending   = 1;
    case Accepted  = 2;
    case Rejected  = 3;
    case Cancelled = 4;

    public function label(): string
    {
        return match ($this) {
            self::Pending   => 'pending',
            self::Accepted  => 'accepted',
            self::Rejected  => 'rejected',
            self::Cancelled => 'cancelled',
        };
    }
}
