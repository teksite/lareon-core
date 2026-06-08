<?php

namespace Lareon\Modules\Auth\App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Lareon\Modules\Auth\App\Enums\ActionType;

class ActionTokenService
{


    private const int TTL = 10 * 60;

    public const int CODE_LENGTH = 40;

    
    /**
     * generate key for cache
     */
    private function key(string $token): string
    {
        return "action_token::" . $token;
    }




    /**
     * generate token
     */
    public function create(string $contact, ActionType $action): string
    {
        $token = $this->generateToken();

        $payload = [
            'contact'    => $contact,
            'action'     => $action->value,
            'used'       => false,
            'hash'       => hash('sha256', $token),
            'created_at' => now()->timestamp,
        ];

        Cache::put($this->key($token), $payload, now()->addSeconds(self::TTL));

        return $token;
    }

    /**
     * @param int|null $length
     * @return string
     */
    public function generateToken(?int $length = null): string
    {
        return Str::random($length ?? self::CODE_LENGTH);
    }


    /**
     * verify token for each action
     */
    public function verify(string $token, string $contact, ActionType $action): bool
    {
        $data = Cache::get($this->key($token));

        if (!$data) return false;

        if (!empty($data['used'])) return false;

        if ( ($data['contact'] ?? null) !== $contact || ($data['action'] ?? null) !== $action->value ) return false;

        $this->forget($token);

        return true;
    }

    /**
     * @param string|null $token
     * @return void
     */
    public function forget(?string $token): void
    {
        if (is_null($token)) return;
        Cache::forget($this->key($token));
    }
}
