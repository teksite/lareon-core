<?php

use Illuminate\Support\HtmlString;
use Lareon\Modules\Captcha\App\Services\CaptchaHtml;
use Lareon\Modules\Captcha\App\Services\CaptchaService;

if (!function_exists('captcha_make')) {
    /**
     * Create a captcha and get its token / image url (or data URI when $inline is true).
     *
     * @return array{token: string, src: string, expires_in: int, img?: string}
     */
    function captcha_make(string $preset = 'default', bool $inline = false): array
    {
        return app(CaptchaService::class)->make($preset, $inline);
    }
}

if (!function_exists('captcha_field')) {
    /**
     * Render a complete captcha block (image, reload button, hidden token, answer input).
     * Call it once per captcha; every call is an independent captcha.
     *
     * @param array $options name, id, class, img[], input[], button[], reload_label
     */
    function captcha_field(string $preset = 'default', array $options = []): HtmlString
    {
        return app(CaptchaService::class)->field($preset, $options);
    }
}

if (!function_exists('captcha_script')) {
    /**
     * The tiny reload script. Safe to call several times, it is printed once per request.
     */
    function captcha_script(?string $nonce = null): HtmlString
    {
        return app(CaptchaService::class)->script($nonce);
    }
}

if (!function_exists('captcha_src')) {
    /**
     * Image URL of an existing captcha token.
     */
    function captcha_src(string $token): string
    {
        return app(CaptchaService::class)->src($token);
    }
}

if (!function_exists('captcha_check')) {
    /**
     * Validate one captcha. When $token is null it is read from the request
     * field "captcha_token" (the default field name of captcha_field()).
     */
    function captcha_check(?string $answer, ?string $token = null, ?string $preset = null): bool
    {
        $token ??= request('captcha' . CaptchaHtml::TOKEN_SUFFIX);

        return app(CaptchaService::class)->check($answer, is_string($token) ? $token : null, $preset);
    }
}

if (!function_exists('captcha_api_check')) {
    /**
     * Validate a captcha created for an API / SPA client (same as captcha_check, token is required).
     */
    function captcha_api_check(?string $answer, ?string $token, ?string $preset = null): bool
    {
        return app(CaptchaService::class)->check($answer, $token, $preset);
    }
}
