<?php


use Carbon\Carbon;
use Morilog\Jalali\Jalalian;

if (!function_exists('admin_menus')) {
    function admin_menus(bool $fresh = false): array
    {
        return app(\Lareon\Steward\App\Service\MenuService::class)->admin($fresh);
    }
}

if (!function_exists('panel_menus')) {
    function user_menus(bool $fresh = false): array
    {
        return app(\Lareon\Steward\App\Service\MenuService::class)->panel($fresh);
    }
}



if (!function_exists('dateAdapter')) {
    function dateAdapter($time, $format = "Y-m-d H:i"): ?string
    {
        if (is_null($time)) return null;
        return config('app.locale') == 'fa' ? Jalalian::forge(Carbon::parse($time))->format($format) : Carbon::parse($time)->format($format);
    }
}


if (!function_exists('dateToJalali')) {
    /**
     * To convert Gregorian date-time to Jalali
     *
     * @param $gregorianDateTime
     * @param string|null $format
     * @return string|null
     */
    function dateToJalali($gregorianDateTime, ?string $format = "Y/m/d H:i:s"): ?string
    {
        try {
            $carbonDate = Carbon::parse($gregorianDateTime);
            return Jalalian::fromCarbon($carbonDate)->format($format);
        } catch (\Exception $e) {
            return null;
        }
    }
}

if (!function_exists('dateToGregorian')) {
    /**
     * To convert Jalali date-time to Gregorian
     *
     * @param $jalaliDate
     * @param string|null $format
     * @return string|null
     */
    function dateToGregorian($jalaliDate, ?string $format = "Y-m-d H:i:s"): ?string
    {

        try {
            $jalaliDateTime = str_replace('/', '-', $jalaliDate);

            $hasTime = preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}(:\d{2})?/', $jalaliDateTime);

            if ($hasTime) {
                $carbonDate = Jalalian::fromFormat('Y-m-d H:i:s', $jalaliDateTime)->toCarbon();
            } else {
                $carbonDate = Jalalian::fromFormat('Y-m-d', $jalaliDateTime)->toCarbon();
            }
            return !is_null($format) ? $carbonDate->format($format) : $carbonDate;
        } catch (\Exception $e) {
            return null;
        }

    }
}


