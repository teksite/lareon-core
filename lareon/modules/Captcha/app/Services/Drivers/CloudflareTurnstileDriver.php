<?php

namespace Lareon\Modules\Captcha\App\Services\Drivers;

/**
 * Cloudflare Turnstile.
 */
class CloudflareTurnstileDriver extends RemoteCaptchaDriver
{
    public function name(): string
    {
        return 'cloudflare';
    }

    protected function globalName(): string
    {
        return 'turnstile';
    }

    protected function defaultApiUrl(): string
    {
        return 'https://challenges.cloudflare.com/turnstile/v0/api.js';
    }

    protected function defaultVerifyUrl(): string
    {
        return 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
    }

    protected function themes(): array
    {
        return ['auto', 'light', 'dark'];
    }

    protected function sizes(): array
    {
        return ['normal', 'compact', 'flexible'];
    }

    protected function renderOptions(): array
    {
        return ['theme', 'size', 'language'];
    }

    /**
     * We keep the token in our own hidden input, so Turnstile must not add its
     * own "cf-turnstile-response" field (two widgets in one form would clash).
     */
    protected function renderExtras(): array
    {
        return ['response-field' => false];
    }
}
