<?php

namespace Lareon\Modules\Ticketing\App\Enums;

enum CloseByType: int
{
    case Admin = 1;
    case User  = 2;
    case Auto  = 3;


    public function label(): string
    {
        return match ($this) {
            self::Admin => __('admin'),
            self::User  => __('user'),
            self::Auto  => __('auto'),
        };
    }


}
