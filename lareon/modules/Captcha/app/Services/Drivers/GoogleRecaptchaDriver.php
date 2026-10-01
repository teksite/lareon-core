<?php

namespace Lareon\Modules\Captcha\App\Services\Drivers;

/**
 * Google reCAPTCHA v2 ("I'm not a robot" checkbox).
 *
 * In regions where google.com is slow or filtered, set
 *   api_url    = https://www.recaptcha.net/recaptcha/api.js
 *   verify_url = https://www.recaptcha.net/recaptcha/api/siteverify
 * (and 'proxy' if your server itself cannot reach Google).
 */
class GoogleRecaptchaDriver extends RemoteCaptchaDriver
{
    public function name(): string
    {
        return 'google';
    }

    protected function globalName(): string
    {
        return 'grecaptcha';
    }

    protected function defaultApiUrl(): string
    {
        return 'https://www.google.com/recaptcha/api.js';
    }

    protected function defaultVerifyUrl(): string
    {
        return 'https://www.google.com/recaptcha/api/siteverify';
    }

    protected function themes(): array
    {
        return ['light', 'dark'];
    }

    protected function sizes(): array
    {
        return ['normal', 'compact'];
    }

    /**
     * reCAPTCHA v2 takes its language from the script URL (hl=...), not from render().
     */
    protected function renderOptions(): array
    {
        return ['theme', 'size'];
    }

    protected function scriptQuery(): array
    {
        $language = $this->widgetOptions('default', [])['language'] ?? null;

        return $language !== null && $language !== 'auto' ? ['hl' => str_replace('_', '-', $language)] : [];
    }
}
