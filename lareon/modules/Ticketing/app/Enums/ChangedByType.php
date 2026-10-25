<?php

namespace Lareon\Modules\Ticketing\App\Enums;

enum ChangedByType: int
{
    case Admin  = 1;
    case User   = 2;
    case Auto   = 3;
    case AI     = 4;
    case System = 5;


    public function label(): string
    {
        return match ($this) {
            self::Admin  => __('admin'),
            self::User   => __('user'),
            self::Auto   => __('auto'),
            self::AI     => (string)config('ticketing.ai.sender_label', __('ai assistant')),
            self::System => __('system'),
        };
    }


}
