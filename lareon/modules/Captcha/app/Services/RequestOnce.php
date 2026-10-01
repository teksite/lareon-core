<?php

namespace Lareon\Modules\Captcha\App\Services;

/**
 * "Do this only once per request" flags.
 *
 * The flag is stored on the current request (not in a static property), so it
 * is reset for every request even in long running workers such as Octane.
 */
final class RequestOnce
{
    /** @var array<string, true> used only when there is no request object (CLI, tests) */
    private static array $fallback = [];

    /**
     * @return bool true the first time it is called for $key in this request
     */
    public static function first(string $key): bool
    {
        $request = function_exists('request') ? request() : null;

        if (is_object($request) && isset($request->attributes)) {
            $name = 'captcha.once.' . $key;

            if ($request->attributes->get($name)) {
                return false;
            }
            $request->attributes->set($name, true);

            return true;
        }

        if (isset(self::$fallback[$key])) {
            return false;
        }
        self::$fallback[$key] = true;

        return true;
    }
}
