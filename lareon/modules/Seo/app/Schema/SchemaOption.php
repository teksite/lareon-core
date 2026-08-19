<?php

namespace Lareon\Modules\Seo\App\Schema;

use Illuminate\Support\Arr;

class SchemaOption
{
    /** @var array<string, array> */
    protected static array $cache = [];

    public static function employmentTypeList(): array
    {
        return static::load('employment-type-list');
    }

    public static function authorList(): array
    {
        return static::load('author-list');
    }

    public static function performerType(): array
    {
        return static::load('performer-type');
    }

    public static function eventAttendanceModeList(): array
    {
        return static::load('event-attendance-mode');
    }

    public static function eventStatusList(): array
    {
        return static::load('event-status-list');
    }

    public static function dayList(): array
    {
        return static::load('day-list');
    }

    public static function timezoneList(): array
    {
        return static::load('timezone-list');
    }

    public static function langList(): array
    {
        return static::load('language-list');
    }
    public static function perTimeList(): array
    {
        return static::load('per-title-list');
    }

    public static function areaList(): array
    {
        return static::load('area-list');
    }

    public static function contactTypeList(): array
    {
        return static::load('contact-type-list');
    }

    public static function currencyList(): array
    {
        return static::load('currency-list');
    }

    public static function languageList(): array
    {
        return static::load('language-list');
    }

    public static function contactOptionList(): array
    {
        return static::load('contact-option-list');
    }

    public static function potentialActionList(): array
    {
        return static::load('potential-action-list');
    }


    public static function articleType(): array
    {
        return static::load('article-type');
    }

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


    public static function eventStatus(): array
    {
        return static::load('event-status');
    }


    public static function availabilityType(): array
    {
        return static::load('availability-type');
    }


    public static function get(string $key, mixed $default = []): mixed
    {
        return Arr::get([

            'employment_type_list'  => static::employmentTypeList(),
            'day_list'              => static::dayList(),
            'area_list'             => static::areaList(),
            'lang_list'             => static::langList(),
            'contact_type_list'     => static::contactTypeList(),
            'contact_option_list'   => static::contactOptionList(),
            'potential_action_list' => static::potentialActionList(),
            'currency_list'         => static::currencyList(),
            'language_list'         => static::languageList(),
            'author_list'           => static::authorList(),
            'event_attendance_mode' => static::eventAttendanceModeList(),
            'event_status_list'     => static::eventStatusList(),
            'timezone_list'         => static::timezoneList(),
            'per_time_list'              => static::perTimeList(),


            'page_types' => static::pageType(),

            'article_type'       => static::articleType(),
            'organization_type'  => static::organizationType(),
            'localBusiness_type' => static::localBusinessType(),
            'performer_type'     => static::performerType(),
            'availability_type'  => static::availabilityType(),
        ], $key, $default);
    }

    protected static function load(string $name): array
    {
        return static::$cache[$name] ??= require __DIR__ . "/data/{$name}.php";
    }

}
