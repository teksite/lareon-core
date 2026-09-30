<?php

namespace Lareon\Modules\Captcha\App\Services\Facade;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array make(string $preset = 'default', bool $inline = false,)
 * @method static string|null image(string $token,)
 * @method static void discard(?string $token,)
 * @method static bool check(?string $answer, ?string $token, ?string $preset = null,)
 * @method static string src(string $token,)
 * @method static string reloadUrl()
 * @method static \Illuminate\Support\HtmlString field(string $preset = 'default', array $options = [],)
 * @method static \Illuminate\Support\HtmlString script(?string $nonce = null,)
 * @method static bool isDisabled()
 * @method static \Lareon\Modules\Captcha\App\Services\CaptchaOptions preset(string $name,)
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
