<?php

namespace Lareon\Steward\App\Enums;

enum CacheAction: string
{
    case STORE = 'store';
    case CLEAR = 'clear';
    case FORGET = 'forget';
    case PRUNE = 'prune';
    case PURGE = 'purge';

    public function label(): string
    {
        return match ($this) {
            self::STORE  => 'Store',
            self::CLEAR  => 'Clear',
            self::FORGET => 'Forget',
            self::PRUNE  => 'Prune',
            self::PURGE  => 'Purge',
        };
    }
}
