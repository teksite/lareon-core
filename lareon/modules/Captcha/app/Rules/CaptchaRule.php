<?php

namespace Lareon\Modules\Captcha\App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Lareon\Modules\Captcha\App\Services\CaptchaHtml;

/**
 * Validates ONE captcha field with the driver set in config (local / google / cloudflare).
 *
 *   'captcha' => [new CaptchaRule()]                       // active driver
 *   'captcha' => [new CaptchaRule('flat')]                 // local driver: must have been created with preset "flat"
 *   'human'   => [new CaptchaRule('math', 'human_token')]  // local driver: custom token field name
 *   'captcha' => [new CaptchaRule(driver: 'cloudflare')]   // force a driver for this field
 *
 * $preset and $tokenField only matter for the local driver; Google / Cloudflare
 * fields carry their whole proof in the response token itself.
 *
 * The rule is implicit: an empty / missing answer FAILS instead of being skipped,
 * so forgetting `required` can never turn the captcha off.
 */
class CaptchaRule implements ValidationRule, DataAwareRule
{
    /**
     * Run even when the value is empty.
     */
    public bool $implicit = true;

    /** @var array<string, mixed> */
    protected array $data = [];

    public function __construct(private readonly ?string $preset = null, private readonly ?string $tokenField = null, private readonly ?string $driver = null) {}

    public function setData(array $data): static
    {
        $this->data = $data;
        return $this;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $token = data_get($this->data, $this->tokenField ?? $attribute.CaptchaHtml::TOKEN_SUFFIX);

        $passes = app('captcha')->check(
            is_scalar($value) ? (string)$value : null,
            is_string($token) ? $token : null,
            $this->preset,
            $this->driver
        );

        if (!$passes) $fail(__('the captcha code is incorrect'));
    }
}
