<?php

namespace Lareon\Modules\Auth\App\Http\Controllers\Web\Auth\OAuths;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Lareon\Modules\Auth\App\Http\Controllers\Controller;
use Lareon\Modules\User\App\Logics\UserLogic;
use Lareon\Modules\User\App\Models\User;
use Teksite\Handler\Data\ServiceResult;
use Teksite\Handler\Facade\Responder;
use Teksite\Handler\Services\ServiceWrapper;

class OAuthsController extends Controller
{
    public function redirect(string $provider,): RedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider,)
    {
        return ServiceWrapper::make(true)->do(function () use ($provider) {
            $social = Socialite::driver($provider)->user();

            if (!$social->getEmail()) return redirect()->route('login')->withErrors(['oauth' => trans('validation.email', ['attribute' => 'email'])]);

            $user = User::query()->firstWhere('email', $social->getEmail()) ?? $this->createUser($social);

            if (!$user->hasVerifiedEmail()) $user->markEmailAsVerified();

            auth()->login($user, remember: true);

            request()->session()->regenerate();

            return Responder::fromResult(new ServiceResult(true, $user), success_url: route('panel.dashboard'))->go();
        });
    }

    /**
     * @throws \Throwable
     */
    private function createUser($social,): User
    {
        return (new UserLogic())->create([
            'name'     => $social->getName() ?: $social->getNickname(),
            'email'    => $social->getEmail(),
            'password' => Str::random(32),
        ])->result;
    }
}
