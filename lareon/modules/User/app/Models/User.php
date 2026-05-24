<?php

namespace Lareon\Modules\User\App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Lareon\Modules\User\Database\Factories\UserFactory;
use Teksite\Authorize\Traits\HasAuthorization;
use Teksite\Extralaravel\Enums\MobilePatterns;
use Teksite\Extralaravel\Rules\MobileRule;

#[UseFactory(UserFactory::class)]
#[Fillable(['name', 'lastname', 'email', 'phone', 'password', 'slug'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasAuthorization;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function rules(string $operation, int|null $userId = null): array
    {
        return match (true) {
            $operation === 'create'=> [
                'name'     => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'password' => 'required|string|min:6|confirmed',
                'phone'    => ['required', 'string', new MobileRule(MobilePatterns::IRAN)],
                'email'    => 'required|string|email|max:255|unique:users',

            ],
            ($operation === 'update' && $userId) => [
                'name'     => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'password' => 'nullable|string|min:6',
                'phone'    => ['required', 'string', new MobileRule(MobilePatterns::IRAN), Rule::unique('users', 'phone')->ignore($userId)],
                'email'    => ['required', 'string', 'email', Rule::unique('users', 'email')->ignore($userId)],
            ],
            default=> throw new \InvalidArgumentException("Operation '{$operation}' is not valid. Allowed: create, update")
        };
    }


    public function path(): ?string
    {
        return Route::has('users.show') ? route('users.show', ['user' => $this]) : null;
    }
}
