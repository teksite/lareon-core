<?php

namespace Lareon\Modules\Auth\App\Http\Requests\Auth\TwoFactorChallenge;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Exceptions\HttpResponseException;
use Laravel\Fortify\Contracts\FailedTwoFactorLoginResponse;
use Lareon\Modules\User\App\Models\User;

trait TwoFactorFormRequestTrait
{

    /**
     * The user attempting the two factor challenge.
     *
     * @var mixed
     */
    protected mixed $challengedUser= null;

    /**
     * Indicates if the user wished to be remembered after login.
     *
     * @var bool
     */
    protected bool $remember =false;

    /**
     * Get the user that is attempting the two factor challenge.
     *
     * @return Authenticatable|User
     */
    public function challengedUser(): Authenticatable|User
    {
        if ($this->challengedUser) {
            return $this->challengedUser;
        }

        $model = app(StatefulGuard::class)->getProvider()->getModel();

        if (!$this->session()->has('login.id') || !$user = $model::find($this->session()->get('login.id'))) {
            throw new HttpResponseException(
                app(FailedTwoFactorLoginResponse::class)->toResponse($this)
            );
        }

        return $this->challengedUser = $user;
    }


    /**
     * Determine if the user wanted to be remembered after login.
     *
     * @return bool
     */
    public function remember(): bool
    {
        if (!$this->remember) {
            $this->remember = $this->session()->pull('login.remember', false);
        }

        return $this->remember;
    }

}
