<?php

namespace Lareon\Steward\App\Enums;

enum PublishStatusEnum: int
{
    case PUBLISHED = 1;
    case DRAFT = 2;
    case POSTPONE = 3;
    case REDIRECT = 4;


    public function label(): string
    {
        return __('enums.publish_status.' . $this->key());
    }

    public function key(): string
    {
        return match ($this) {
            self::PUBLISHED => 'published',
            self::DRAFT     => 'drafted',
            self::POSTPONE  => 'postponed',
            self::REDIRECT  => 'redirected',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::PUBLISHED => 'text-green-600 bg-green-100',
            self::DRAFT     => 'text-gray-600 bg-gray-100',
            self::POSTPONE  => 'text-amber-600 bg-amber-100',
            self::REDIRECT  => 'text-cyan-600 bg-cyan-100',
        };
    }


    public function toHtml(): string
    {
        return sprintf(
            "<span class='%s font-bold text-xs px-3 py-1 rounded-xl'>%s</span>",
            $this->badgeClasses(),
            e($this->label())
        );
    }
}
