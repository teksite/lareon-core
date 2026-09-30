<?php

namespace Lareon\Modules\Captcha\App\Services\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Http\Response|array create(string $config = 'default', bool $api = false)
 * @method static bool check(string $value)
 * @method static bool check_api(string $value, string $key, string $config = 'custom')
 * @method static string src(string $config = 'default')
 * @method static string img(string $config = 'default', array $attrs = [])
 *
 * @see \Lareon\Modules\Captcha\App\Services\CaptchaService
 */
class Captcha extends Facade
{
    /**
     * Must match the key used in CaptchaServiceProvider::registerCaptcha().
     */
    protected static function getFacadeAccessor(): string
    {
        return 'captcha';
    }
}
