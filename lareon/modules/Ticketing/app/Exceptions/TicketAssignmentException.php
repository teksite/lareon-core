<?php

namespace Lareon\Modules\Ticketing\App\Exceptions;

use Exception;

class TicketAssignmentException extends Exception
{
    public static function alreadyTaken(): self
    {
        return new self('This ticket has already been claimed by another admin.');
    }

    public static function notHolder(): self
    {
        return new self('You are not currently the owner of this ticket and cannot refer it.');
    }

    public static function notAssignee(): self
    {
        return new self('This referral is not assigned to you.');
    }

    public static function alreadyDecided(): self
    {
        return new self('A decision has already been made on this referral.');
    }

    public static function cannotReferToSelf(): self
    {
        return new self('You cannot refer a ticket to yourself.');
    }
}
