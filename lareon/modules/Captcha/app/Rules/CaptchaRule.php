<?php

namespace Lareon\Modules\Captcha\App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Lareon\Modules\Captcha\App\Services\CaptchaHtml;
use Lareon\Modules\Captcha\App\Services\CaptchaService;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * Validates ONE captcha field.
 *
 *   'captcha' => [new CaptchaRule()]                       // token in "captcha_token"
 *   'captcha' => [new CaptchaRule('flat')]                 // must have been created with preset "flat"
 *   'human'   => [new CaptchaRule('math', 'human_token')]  // custom token field name
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

    public function __construct(private readonly ?string $preset = null, private readonly ?string $tokenField = null,) {
    }

    public function setData(array $data): static
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $token = data_get($this->data, $this->tokenField ?? $attribute . CaptchaHtml::TOKEN_SUFFIX);

        $passes = app(CaptchaService::class)->check(
            is_scalar($value) ? (string)$value : null,
            is_string($token) ? $token : null,
            $this->preset
        );

        if (!$passes) $fail(__('the captcha code is incorrect'));

    }
}
