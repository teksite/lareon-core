<?php

namespace Lareon\Modules\Fence\App\Enums;

enum GuardType: int
{
    case WHITE = 1;
    case BLACK = 0;

    public function label(): string
    {
        return match ($this) {
            self::WHITE => trans('white'),
            self::BLACK   => trans('black'),
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

    public function badgeClasses(): string
    {
        return match ($this) {
            self::WHITE => 'text-green-600 bg-green-100',
            self::BLACK   => 'text-red-600 bg-red-100',
        };
    }

}
