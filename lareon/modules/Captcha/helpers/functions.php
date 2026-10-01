<?php

use Illuminate\Support\HtmlString;
use Lareon\Modules\Captcha\App\Services\CaptchaHtml;
use Lareon\Modules\Captcha\App\Services\CaptchaService;

if (!function_exists('captcha_field')) {
    /**
     * Render ONE captcha block of the driver set in config (local / google / cloudflare),
     * followed by the script it needs (printed once per request).
     * Call it once per captcha; every call is an independent captcha.
     *
     * @param array $options name, id, class, widget[], driver, script, nonce
     * @throws \Random\RandomException
     */
    function captcha_field(string $preset = 'default', array $options = []): HtmlString
    {
        return app('captcha')->field($preset, $options);
    }
}

if (!function_exists('captcha_script')) {
    /**
     * Only needed when a field was rendered with ['script' => false].
     */
    function captcha_script(?string $nonce = null, ?string $driver = null): HtmlString
    {
        return app('captcha')->script($nonce, $driver);
    }
}

if (!function_exists('captcha_check')) {
    /**
     * Validate one captcha with the active driver.
     *
     * For the local driver the token is read from the request field "captcha_token"
     * when it is not given. Remote drivers (google / Cloudflare) ignore it.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    function captcha_check(?string $answer, ?string $token = null, ?string $preset = null): bool
    {
        $token ??= request('captcha'.CaptchaHtml::TOKEN_SUFFIX);

        return app('captcha')->check($answer, is_string($token) ? $token : null, $preset);
    }
}

if (!function_exists('captcha_api_check')) {
    /**
     * Validate a captcha for an API / SPA client. Same as captcha_check() but the token
     * is never read from the request implicitly.
     *
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    function captcha_api_check(?string $answer, ?string $token = null, ?string $preset = null): bool
    {
        return app('captcha')->check($answer, $token, $preset);
    }
}

if (!function_exists('captcha_config')) {
    /**
     * Public info about the active driver for an SPA / mobile app that renders the widget itself:
     * ['driver' => 'google', 'site_key' => '...', 'script' => '...'] (never contains secrets).
     */
    function captcha_config(): array
    {
        return app('captcha')->clientConfig();
    }
}

if (!function_exists('captcha_make')) {
    /**
     * Create a LOCAL image captcha and get its token / image url (or data URI when $inline is true).
     * Only for the built-in driver; google / Cloudflare widgets are rendered by the browser.
     *
     * @return array{token: string, src: string, expires_in: int, img?: string}
     */
    function captcha_make(string $preset = 'default', bool $inline = false): array
    {
        return app(CaptchaService::class)->make($preset, $inline);
    }
}

if (!function_exists('captcha_src')) {
    /**
     * Image URL of an existing LOCAL captcha token.
     */
    function captcha_src(string $token): string
    {
        return app(CaptchaService::class)->src($token);
    }
}
