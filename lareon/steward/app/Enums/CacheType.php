<?php

namespace Lareon\Steward\App\Enums;

enum CacheType: string
{
    case AUTH = 'auth';
    case SANCTUM = 'sanctum';
    case CACHE = 'cache';
    case CONFIG = 'config';
    case EVENT = 'event';
    case ROUTE = 'route';
    case VIEW = 'view';

    case OPTIMIZE = 'optimize';
    case SCHEDULE = 'schedule';
    case COMPILED_FILE = 'compiled_file';

    public function label(): string
    {
        return match ($this) {
            self::AUTH          => 'Auth',
            self::SANCTUM       => 'Sanctum',
            self::CACHE         => 'Cache',
            self::CONFIG        => 'Config',
            self::VIEW          => 'View',
            self::ROUTE         => 'Route',
            self::EVENT         => 'Event',
            self::SCHEDULE      => 'Schedule',
            self::OPTIMIZE      => 'Optimize',
            self::COMPILED_FILE => 'Compiled Files',
        };
    }

    public function actions(): array
    {
        return match ($this) {

            self::AUTH, self::SANCTUM, self::SCHEDULE, self::COMPILED_FILE     => [
                CacheAction::CLEAR,
            ],

            self::CACHE                                                        => [
                CacheAction::CLEAR,
                CacheAction::FORGET,
                CacheAction::PRUNE,
                CacheAction::PURGE,
            ],

            self::CONFIG, self::EVENT, self::OPTIMIZE, self::ROUTE, self::VIEW => [
                CacheAction::STORE,
                CacheAction::CLEAR,
            ],
        };
    }
}
