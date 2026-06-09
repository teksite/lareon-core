<?php

namespace Lareon\Modules\Auth\App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Lareon\Modules\Auth\App\Enums\ActionType;

class AuthTokenService
{
    private const int DEFAULT_TTL = 600;

    public const int TOKEN_LENGTH = 40;


    /**
     * generate key for cache
     */
    private function key(string $token): string
    {
        return "action_token::{$token}";
    }


    /**
     * Cache key for contact/action lookup.
     */
    private function lookupKey(string $contact, ActionType $action): string
    {
        return "action_token_lookup::{$action->value}::{$contact}";
    }

    /**
     * generate token
     */
    public function create(string $contact, ActionType $action, ?int $ttl = null): string
    {
        $ttl ??= self::DEFAULT_TTL;

        $this->revokeByContact($contact, $action);

        $token = $this->generateToken();

        $payload = [
            'contact'    => $contact,
            'action'     => $action->value,
            'created_at' => now()->timestamp,
            'expires_at' => now()->addSeconds($ttl)->timestamp,
        ];

        Cache::put($this->key($token), $payload, now()->addSeconds($ttl));

        Cache::put($this->lookupKey($contact, $action), $token, now()->addSeconds($ttl));

        return $token;
    }

    /**
     * Generate secure random token.
     */
    public function generateToken(int $length = self::TOKEN_LENGTH): string
    {
        return Str::random($length);
    }


    /**
     * verify token for each action
     */
    public function verify(string $token, string $contact, ActionType $action): bool
    {
        $data = Cache::get($this->key($token));

        if (!$data) return false;

        if (($data['contact'] ?? null) !== $contact) return false;
        if (($data['action'] ?? null) !== $action->value) return false;

        if (($data['expires_at'] ?? 0) < now()->timestamp) {
            $this->forget($token);
            return false;
        }

        $this->forget($token);
        return true;
    }

    /**
     * Remaining lifetime in seconds.
     */
    public function remainingTime(string $contact, ActionType $action): int
    {
        $token = Cache::get($this->lookupKey($contact, $action));

        if (!$token) return 0;

        $data = Cache::get($this->key($token));

        if (!$data) return 0;

        return max(($data['expires_at'] ?? 0) - now()->timestamp, 0);
    }

    /**
     * Remove token.
     */
    public function forget(string $token): void
    {
        $data = Cache::get($this->key($token));

        if ($data) {
            Cache::forget($this->lookupKey($data['contact'], ActionType::from($data['action'])));
        }

        Cache::forget($this->key($token));
    }

    /**
     * Remove token by contact/action.
     */
    public function revokeByContact(string $contact, ActionType $action): void
    {
        $token = Cache::get($this->lookupKey($contact, $action));

        if ($token) { Cache::forget($this->key($token)); }

        Cache::forget($this->lookupKey($contact, $action));
    }
}
