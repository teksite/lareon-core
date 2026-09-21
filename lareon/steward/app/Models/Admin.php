<?php

namespace Lareon\Steward\App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Lareon\Modules\User\Database\Factories\UserFactory;
use Lareon\Steward\App\Models\Scopes\AdminActiveScope;
use Lareon\Steward\Database\Factories\AdminFactory;
use Teksite\Authorize\Traits\HasAuthorization;


#[UseFactory(AdminFactory::class)]
#[Fillable(['name', 'email', 'password', 'parent_id', 'active'])]
#[Hidden(['password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'])]
//#[ScopedBy([AdminActiveScope::class])]
class Admin extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasAuthorization, TwoFactorAuthenticatable, HasApiTokens;

    protected $table = 'users_admins';


    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public static function rules(string $operation, int|null $adminId = null,): array
    {
        return match (true) {
            $operation === 'create' => [
                'name'     => 'required|string|max:255',
                'active'   => 'required|in:0,1',
                'password' => 'required|string|min:6|confirmed',
                'email'    => 'required|string|email|max:255|unique:users_admins',
            ],
            ($operation === 'update' && $adminId) => [
                'name'     => 'required|string|max:255',
                'active'   => 'required|in:0,1',
                'password' => 'nullable|string|min:6|confirmed',
                'email'    => ['required', 'string', 'email', Rule::unique('users_admins', 'email')->ignore($adminId)],
            ],
            default => throw new \InvalidArgumentException("Operation '{$operation}' is not valid. Allowed: create, update")
        };
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }


    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
