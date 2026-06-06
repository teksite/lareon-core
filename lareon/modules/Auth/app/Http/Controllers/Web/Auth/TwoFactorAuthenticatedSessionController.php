<?php

namespace Lareon\Modules\Auth\App\Http\Controllers\Web\Auth;

use Illuminate\Contracts\Auth\StatefulGuard;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse;
use Lareon\Modules\Auth\App\Http\Controllers\Controller;
use Lareon\Modules\Auth\App\Http\Requests\Auth\TwoFactorChallenge\RecoveryRequest;
use Lareon\Modules\Auth\App\Http\Requests\Auth\TwoFactorChallenge\TotpRequest;

class TwoFactorAuthenticatedSessionController extends Controller
{

    public function __construct(protected StatefulGuard $guard)
    {
    }

    public function viaOTP(TotpRequest $request)
    {
        $this->guard->login($request->challengedUser(), $request->remember());

        $request->session()->regenerate();

        return app(TwoFactorLoginResponse::class);
    }



    public function viaRecovery(RecoveryRequest $request)
    {
        $this->guard->login($request->challengedUser(), $request->remember());

        $request->session()->regenerate();

        return app(TwoFactorLoginResponse::class);
    }
}
