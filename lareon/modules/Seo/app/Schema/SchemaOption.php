<?php

namespace Lareon\Modules\Seo\App\Schema;

use Illuminate\Support\Arr;

class SchemaOption
{
    /** @var array<string, array> */
    protected static array $cache = [];

    public static function pageType(): array
    {
        return static::load('page-type');
    }

    public static function organizationType(): array
    {
        return static::load('organization-type');
    }

    public static function localBusinessType(): array
    {
        return static::load('local-business-type');
    }

    public static function contactType(): array
    {
        return static::load('contact-type');
    }

    public static function contactOption(): array
    {
        return static::load('contact-option');
    }

    public static function eventStatus(): array
    {
        return static::load('event-status');
    }

    public static function eventPerformance(): array
    {
        return static::load('event-performance');
    }

    public static function attendanceMode(): array
    {
        return static::load('attendance-mode');
    }


    public static function get(string $key, mixed $default = []): mixed
    {
        return Arr::get([
//            'pageType'           => static::pageType(),
//            'organization_type'  => static::organizationType(),
            'localBusiness_type' => static::localBusinessType(),
//            'contact_type'       => static::contactType(),
//            'contactOption'      => static::contactOption(),
//            'eventStatus'        => static::eventStatus(),
//            'eventPerformance'   => static::eventPerformance(),
//            'attendanceMode'     => static::attendanceMode(),
        ], $key, $default);
    }

    protected static function load(string $name): array
    {
        return static::$cache[$name] ??= require __DIR__ . "/data/{$name}.php";
    }

}
