<?php

namespace Lareon\Modules\Seo\App\Enums;

enum SeoStateEnum: int
{
    case Inactive = 0;
    case Activate = 1;

    public function label(): string
    {
        return match ($this) {
            self::Inactive => 'inactive',
            self::Activate => 'active',
        };
    }

    public function isPublishable(): bool
    {
        return $this === self::Activate;
    }
}
