<?php

namespace Lareon\Modules\Auth\App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Lareon\Modules\Auth\App\Enums\ContactType;
use Lareon\Modules\Auth\App\Enums\ActionType;

class OtpService
{
    const int CODE_LENGTH = 6;
    const array CHAR_CODE = [1, 2, 3, 4, 5, 6, 7, 8, 9];

    const int SMS_EXPIRATION = 60; //in seconds
    const int EMAIL_EXPIRATION = 300; //in seconds

    const int MAX_ATTEMPTS = 5;

    const true ENCRYPT = true;

    /**
     * generate random code
     */
    private function randomCode(): string
    {
        $code = '';
        for ($i = 0; $i < self::CODE_LENGTH; $i++) {
            $code .= Arr::random(self::CHAR_CODE);
        }
        return $code;
    }


    /**
     * generate cache key
     *
     */
    private function key(string $to, ActionType $action): string
    {
        $gateway = ContactType::detect($to);
        return "otp::{$action->value}::{$gateway->value}::" . sha1($to);
    }

    /**
     * calculate ttl for cache
     */
    private function ttl(string $to, ?int $customTtl = null): int
    {
        if ($customTtl !== null) return max(1, $customTtl);

        return ContactType::detect($to) === ContactType::PHONE
            ? self::SMS_EXPIRATION
            : self::EMAIL_EXPIRATION;
    }

    /**
     * GENERATE OTP
     */
    public function generate(string $to, ActionType $action, null|int $ttl = null): false|array
    {
        $gateway = ContactType::detect($to);

        if (!$gateway) return false;

        $key = $this->key($to, $action);

        $ttl = $this->ttl($to, $ttl);

        $code = $this->randomCode();

        $payload = [
            'code'       => self::ENCRYPT ? hash('sha256', $code) : $code,
            'attempts'   => 0,
            'created_at' => now()->timestamp,
            'expires_at' => now()->addSeconds($ttl)->timestamp,
            'verified'   => false,
        ];

        Cache::put($key, $payload, now()->addSeconds($ttl));

        return [
            'code'       => $code,
            'to'         => $to,
            'expires_at' => $payload['expires_at'],
            'ttl'        => $ttl,
        ];
    }

    /**
     * VERIFY OTP
     */
    public function verify(string $code, string $to, ActionType $action): bool
    {
        $key = $this->key($to, $action);

        $data = Cache::get($key);

        if (!$data)   return false;

        if (($data['expires_at'] ?? 0) < now()->timestamp) {
            Cache::forget($key);
            return false;
        }

        if (!empty($data['verified']) || $data['verified'] === true) {
            return false;
        }

        if (($data['attempts'] ?? 0) >= self::MAX_ATTEMPTS) {
            Cache::forget($key);
            return false;
        }

        if (hash('sha256', $code) !== $data['code']) {
            $data['attempts'] = ($data['attempts'] ?? 0) + 1;

            Cache::put($key, $data, now()->addSeconds($data['expires_at'] - now()->timestamp));

            return false;
        }
        Cache::forget($key);
        return true;
    }


    public function remainingTime(string $to, ActionType $action , bool $testing = false): int
    {
       if ($testing) return 0;
        $data = Cache::get($this->key($to, $action));

        if (!$data || !isset($data['expires_at'])) {
            return 0;
        }

        return max($data['expires_at'] - now()->timestamp, 0);
    }

}
