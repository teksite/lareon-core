<?php

namespace Lareon\Modules\Ticketing\App\Enums;

enum TicketStatusType: int
{
    case Opened        = 1;
    case WaitingAdmin = 2;
    case InProgress   = 3;
    case Answered      = 4;
    case Closed        = 0;


    public function label(): string
    {
        return match ($this) {
            self::Closed => __('close'),
            self::Opened => __('open'),
            self::WaitingAdmin => __('waiting'),
            self::InProgress => __('in progress'),
            self::Answered => __('answered'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Opened => '#6366f1',
            self::InProgress => '#d97706',
            self::Answered => '#16a34a',
            self::WaitingAdmin => '#0891b2',
            self::Closed => '#6b7280',
        };
    }

    public function isClosed(): bool
    {
        return $this === self::Closed;
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $status) => [$status->value => $status->label()])
            ->all();
    }

}
