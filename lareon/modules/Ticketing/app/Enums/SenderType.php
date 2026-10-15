<?php

namespace Lareon\Modules\Ticketing\App\Enums;

enum SenderType: int
{
    case User   = 1;
    case Admin  = 2;
    case System = 3;
    case Ai     = 4;


    public function label(): string
    {
        return match ($this) {
            self::User   => __('user'),
            self::Admin  => __('admin'),
            self::System => __('system'),
            self::Ai     => __('ai'),
            default      => __('unknown')
        };
    }



}
