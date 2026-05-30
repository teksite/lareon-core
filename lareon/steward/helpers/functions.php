<?php


use Carbon\Carbon;
use Morilog\Jalali\Jalalian;

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


if (!function_exists('smart_date')) {

    function smart_date( ?string $dateTime = null,  ?string $targetTimeZone = null, string $format = 'Y-m-d H:i:s'): ?string
    {
        $date = $dateTime ? Carbon::parse($dateTime) : Carbon::now();

        if ($targetTimeZone) {
            return $date->tz($targetTimeZone)->format($format);
        }

        $laravelTimezone = Config::get('app.timezone');
        $serverTimezone = date_default_timezone_get();

        if ($laravelTimezone === $serverTimezone) {
            return $date->format($format);
        }

        return $date->tz($laravelTimezone)->format($format);
    }
}


if (!function_exists('dateAdapter')) {
    function dateAdapter(null|Carbon|string $dateTime, $format = "Y-m-d H:i"): ?string
    {
        if (is_null($dateTime)) return null;

        $newDateTime = smart_date($dateTime);
        return config('app.locale') == 'fa' ? Jalalian::forge(Carbon::parse($newDateTime))->format($format) : Carbon::parse($newDateTime)->format($format);
    }
}
if (!function_exists('userCan')) {
    /**
     * check current user is authenticated and then check have permission(s) or not
     *
     * @param string|array|null $permission
     * @return bool
     */
    function userCan(string|array|null $permission=null):bool
    {
        $user = Auth::user();
        if (is_null($user)) return false;

        $permissions = (array)$permission;
        if (empty($permissions)) return true;

        return $user->canAny($permissions);
    }

}
