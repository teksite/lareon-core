<?php

namespace Lareon\Steward\App\Enums;

enum PublishStatusEnum :int
{
    case PUBLISHED = 1;
    case DRAFT = 2;
    case POSTPONE = 3;
    case REDIRECT = 4;

    public function getName(): string
    {
        return match ($this) {
            self::PUBLISHED => 'published',
            self::DRAFT => 'drafted',
            self::POSTPONE => 'postponed',
            self::REDIRECT => 'redirected',
        };
    }

    public function view(): string
    {
        $classes =match ($this) {
            self::PUBLISHED => 'text-green-600 bg-green-100',
            self::DRAFT =>  'text-gray-600 bg-gray-100',
            self::POSTPONE => 'text-amber-600 bg-amber-100',
            self::REDIRECT => 'text-cyan-600 bg-cyan-100',
        };
        return "<span class='$classes font-bold text-xs px-3 py-1 rounded-xl'>".__($this->getName())."</span>";

    }

}
