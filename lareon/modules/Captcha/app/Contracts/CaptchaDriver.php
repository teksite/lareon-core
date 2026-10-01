<?php

namespace Lareon\Modules\Captcha\App\Contracts;

use Illuminate\Support\HtmlString;

/**
 * A captcha provider (local image, Google reCAPTCHA, Cloudflare Turnstile ...).
 *
 * Every driver renders its own form block and validates the value the user
 * submitted for it, so forms never need to know which provider is active.
 */
interface CaptchaDriver
{
    /**
     * Short lowercase name used in config, e.g. "local", "google", "Cloudflare".
     */
    public function name(): string;

    /**
     * HTML of ONE captcha block (call it once per captcha, each call is independent).
     *
     * @param array $options name, id, class, plus driver specific options
     */
    public function field(string $preset = 'default', array $options = []): HtmlString;

    /**
     * JavaScript / script tags this driver needs. Printed once per request.
     */
    public function script(?string $nonce = null): HtmlString;

    /**
     * Validate ONE captcha.
     *
     * @param string|null $answer What the user submitted for the field
     *                            (typed text for "local", widget response token for remote drivers)
     * @param string|null $token  Hidden token of the local captcha (ignored by remote drivers)
     * @param string|null $preset Preset the captcha must have been created with (local driver only)
     */
    public function check(?string $answer, ?string $token = null, ?string $preset = null): bool;

    /**
     * Public (non-secret) information for SPAs / mobile apps that render the widget themselves.
     *
     * @return array<string, mixed>
     */
    public function clientConfig(): array;
}
