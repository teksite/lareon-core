<?php

namespace Lareon\Modules\Auth\App\Services;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Crypt;
use Lareon\Modules\Auth\App\Models\PersonalAccessToken;
use Lareon\Modules\User\App\Models\User;


class AuthTokenService
{
    const string PREFIX = 'x_web_token';
    const string TOKEN_PREFIX = "Bearer ";
    const bool ENCRYPT_TOKEN = true;
    const int TTL = 28 * 24 * 3600;

    /**
     * Create new access token.
     */
    public function create(User|Authenticatable $user): string
    {
        $token = $user->createToken(self::PREFIX, expiresAt: now()->addSeconds(self::TTL))->plainTextToken;

        return self::ENCRYPT_TOKEN ? Crypt::encrypt($token) : $token;
    }

    /**
     * delete the token
     */
    public function delete(?string $token): void
    {
        $tokenModel = $this->findToken($token);

        if (!$tokenModel) return;

        $tokenModel->delete();

    }

    /**
     * Delete all the user tokens.
     */
    public function flush(Authenticatable|User $user): void
    {
        $user->tokens()->delete();
    }

    /**
     * Get current request token.
     */
    public function getCurrentToken(bool $extracted = true): string
    {
        $authorizationToken = request()->header('Authorization', '') ?? request()->cookie(self::PREFIX);

        return $extracted ? str_replace(self::TOKEN_PREFIX, '', $authorizationToken) : $authorizationToken;
    }


    /**
     * Check if current request has valid token.
     */
    public function hasValidToken(): bool
    {
        return $this->currentTokenModel() !== null;
    }

    /**
     * Resolve token model.
     */
    public function findToken(?string $token): ?PersonalAccessToken
    {
        if (blank($token)) return null;
        $token = $this->normalizeToken($token);

        if (!$token) return null;

        return PersonalAccessToken::findToken($token);
    }


    /**
     * Normalize token.
     */
    protected function normalizeToken(string $token): ?string
    {
        if (!self::ENCRYPT_TOKEN) return $token;

        try {
            return Crypt::decryptString($token);
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Revoke current authenticated token.
     */
    public function revokeCurrentToken(): void
    {
        $this->currentTokenModel()?->delete();
    }

    /**
     * Revoke all tokens except current one.
     */
    public function revokeOtherTokens(User|Authenticatable $user): void
    {
        $currentTokenId = auth()->user()?->currentAccessToken()?->id;

        $user->tokens()
             ->when($currentTokenId, fn($query) => $query->whereKeyNot($currentTokenId))
             ->delete();
    }

    /**
     * Get current authenticated token model.
     */
    public function currentTokenModel(): ?PersonalAccessToken
    {
        return $this->findToken($this->getCurrentToken());
    }

}
