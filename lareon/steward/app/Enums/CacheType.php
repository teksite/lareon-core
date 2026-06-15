<?php

namespace Lareon\Steward\App\Enums;

enum CacheType: string
{
    case AUTH = 'auth';
    case CACHE = 'cache';
    case CONFIG = 'config';
    case EVENT = 'event';
    case OPTIMIZE = 'optimize';
    case ROUTE = 'route';
    case SANCTUM = 'sanctum';
    case SCHEDULE = 'schedule';
    case VIEW = 'view';
    case COMPILED_FILE = 'compiled_file';

    public function label(): string
    {
        return match ($this) {
            self::AUTH          => 'Auth',
            self::CACHE         => 'Cache',
            self::CONFIG        => 'Config',
            self::EVENT         => 'Event',
            self::OPTIMIZE      => 'Optimize',
            self::ROUTE         => 'Route',
            self::SANCTUM       => 'Sanctum',
            self::SCHEDULE      => 'Schedule',
            self::VIEW          => 'View',
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
