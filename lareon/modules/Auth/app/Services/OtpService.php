<?php

namespace Lareon\Modules\Auth\App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Lareon\Modules\Auth\App\Enums\ContactType;
use Lareon\Modules\Auth\App\Enums\ActionType;

class OtpService
{
    public const int CODE_LENGTH = 6;

    public const int SMS_EXPIRATION = 60;

    public const int EMAIL_EXPIRATION = 300;

    public const int MAX_ATTEMPTS = 5;

    public const bool ENCRYPT = true;

    /**
     * Generate secure OTP.
     */
    private function randomCode(): string
    {
        return str_pad((string)random_int(0, (10 ** self::CODE_LENGTH) - 1), self::CODE_LENGTH, '0', STR_PAD_LEFT);
    }

    /**
     * Cache key.
     */
    private function key(string $to, ActionType $action): string
    {
        $contactType = ContactType::detect($to);
        return sprintf('otp::%s:%s:%s', $action->value, $contactType->value, sha1($to));
    }

    /**
     * Resolve ttl.
     */
    private function ttl(string $to, ?int $customTtl = null): int
    {
        if ($customTtl !== null) return max(1, $customTtl);

        return ContactType::detect($to) === ContactType::PHONE
            ? self::SMS_EXPIRATION
            : self::EMAIL_EXPIRATION;
    }

    /**
     * Generate OTP.
     */
    public function generate(string $to, ActionType $action, ?int $ttl = null): array
    {
        $ttl = $this->ttl($to, $ttl);
        $code = $this->randomCode();

        $payload = [
            'code'       => self::ENCRYPT ? hash('sha256', $code) : $code,
            'attempts'   => 0,
            'created_at' => now()->timestamp,
            'expires_at' => now()->addSeconds($ttl)->timestamp,
        ];

        Cache::put($this->key($to, $action), $payload, now()->addSeconds($ttl));

        return [
            'code'       => $code,
            'to'         => $to,
            'ttl'        => $ttl,
            'expires_at' => $payload['expires_at'],
        ];
    }

    /**
     * Verify OTP.
     */
    public function verify(string $code, string $to, ActionType $action): bool
    {
        $key = $this->key($to, $action);

        $data = Cache::get($key);

        if (!$data) return false;

        if (($data['expires_at'] ?? 0) < now()->timestamp) {
            Cache::forget($key);
            return false;
        }

        if (($data['attempts'] ?? 0) >= self::MAX_ATTEMPTS) {
            Cache::forget($key);
            return false;
        }

        $isValid = self::ENCRYPT
            ? hash_equals($data['code'], hash('sha256', $code))
            : hash_equals($data['code'], $code);

        if (!$isValid) {
            $data['attempts'] = ($data['attempts'] ?? 0) + 1;
            $remaining = max($data['expires_at'] - now()->timestamp, 1);

            Cache::put($key, $data, now()->addSeconds($remaining));
            return false;
        }

        Cache::forget($key);
        return true;
    }

    /**
     * Delete OTP.
     */
    public function forget(string $to, ActionType $action): void
    {
        Cache::forget($this->key($to, $action));
    }

    /**
     * Remaining time in seconds.
     */
    public function remainingTime(string $to, ActionType $action, bool $testing = false): int
    {
        if ($testing) return 0;

        $data = Cache::get($this->key($to, $action));

        if (!$data || !isset($data['expires_at'])) return 0;


        return max($data['expires_at'] - now()->timestamp, 0);
    }

    /**
     * Check whether OTP exists.
     */
    public function exists(string $to, ActionType $action): bool
    {
        return Cache::has($this->key($to, $action));
    }
}
