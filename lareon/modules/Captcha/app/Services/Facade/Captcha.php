<?php

namespace Lareon\Modules\Captcha\App\Services\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Lareon\Modules\Captcha\App\Contracts\CaptchaDriver driver(?string $name = null)
 * @method static \Lareon\Modules\Captcha\App\Services\CaptchaService local()
 * @method static string defaultDriver()
 * @method static \Lareon\Modules\Captcha\App\Services\CaptchaManager extend(string $name, \Closure $creator)
 * @method static \Illuminate\Support\HtmlString field(string $preset = 'default', array $options = [])
 * @method static \Illuminate\Support\HtmlString script(?string $nonce = null, ?string $driver = null)
 * @method static bool check(?string $answer, ?string $token = null, ?string $preset = null, ?string $driver = null)
 * @method static array clientConfig(?string $driver = null)
 * @method static bool isDisabled()
 *
 * Image captcha only (local driver):  Captcha::local()->make('flat') / ->image($token) / ->discard($token)
 *
 * @see \Lareon\Modules\Captcha\App\Services\CaptchaManager
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
