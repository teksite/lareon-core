<?php

namespace Lareon\Steward\App\Enums;

enum PublishStatusEnum: int
{
    case PUBLISHED = 1;
    case DRAFTED = 2;


    public function label(): string
    {
        return match ($this) {
            self::PUBLISHED => 'published',
            self::DRAFTED   => 'drafted',
        };
    }

    public function key(): string
    {
        return match ($this) {
            self::PUBLISHED => 'published',
            self::DRAFTED   => 'drafted',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::PUBLISHED => 'text-green-600 bg-green-100',
            self::DRAFTED   => 'text-gray-600 bg-gray-100',
        };
    }


    public function toHtml(): string
    {
        return sprintf(
            "<span class='%s font-bold text-xs px-3 py-1 rounded-xl select-none'>%s</span>",
            $this->badgeClasses(),
            e($this->label())
        );
    }
}
