<?php

namespace Lareon\Modules\Ticketing\App\Enums;

enum PriorityType: int
{
    case Low    = 4;
    case Normal = 3;
    case High   = 2;
    case Urgent = 1;


    public function label(): string
    {
        return match ($this) {
            self::Low    => __('low'),
            self::Normal => __('normal'),
            self::High   => __('high'),
            self::Urgent => __('urgent'),
            default      => throw new \Exception('Unknown priority type'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Low    => '#6b7280',
            self::Normal => '#2563eb',
            self::High   => '#d97706',
            self::Urgent => '#dc2626',
            default      => '#2563ed',

        };
    }

}
