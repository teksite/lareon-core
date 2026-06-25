<?php

namespace Lareon\Modules\Auth\App\Providers;

use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;
use Lareon\Modules\Auth\App\Actions\Fortify\AuthenticationUser;
use Lareon\Modules\Auth\App\Actions\Fortify\CreateNewUser;
use Lareon\Modules\Auth\App\Actions\Fortify\ResetUserPassword;
use Lareon\Modules\Auth\App\Actions\Fortify\UpdateUserPassword;
use Lareon\Modules\User\App\Models\User;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureActions();
        $this->bootViews();
        $this->configureRateLimiting();
    }

    private function bootViews(): void
    {
        Fortify::registerView(fn() => View::first(['pages.auth.register', 'auth::authentication.pages.register']));
        Fortify::loginView(fn() => View::first(['pages.auth.login', 'auth::authentication.pages.login']));
        Fortify::verifyEmailView(fn() => View::first(['pages.auth.verify-email', 'auth::authentication.pages.verify-email']));
        Fortify::twoFactorChallengeView(fn() => View::first(['pages.auth.2fa-challenge', 'auth::authentication.pages.2fa-challenge']));
        Fortify::confirmPasswordView(fn() => View::first(['pages.auth.confirm-password', 'auth::authentication.pages.confirm-password']));
//        Fortify::requestPasswordResetLinkView(fn() => View::first(['pages.auth.forgot-password', 'lareon::authentication.pages.forgot-password']));
//        Fortify::resetPasswordView(fn() => View::first(['pages.auth.reset-password', 'lareon::authentication.pages.reset-password']));


    }
    private function configureActions(): void
    {
        Fortify::authenticateUsing($this->authenticationUser());
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);
//        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
//        Fortify::confirmPasswordsUsing($this->confirmPassword());


    }

    /**
     * @return \Closure
     */
    protected function authenticationUser(): \Closure
    {
        return function (Request $request) {
            $username = $request->input('username');
            $password = $request->input('password');

            $user= User::query()
                       ->where('email', $username)
                       ->orWhere('phone', $username)
                       ->first();

            if ($user && Hash::check($password, $user->password)) {
                return $user;
            }
            return null;
        };
    }



    /**
     * @return \Closure
     */
    protected function confirmPassword(): \Closure
    {
        return function (User $user, string $password): bool {
            return Hash::check($password, $user->password);
        };
    }

    private function configureRateLimiting(): void
    {
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('passkeys', function (Request $request) {
            $credentialId = $request->input('credential.id');

            return Limit::perMinute(10)->by(
                ($credentialId ?: $request->session()->getId()).'|'.$request->ip(),
            );
        });
    }
}
