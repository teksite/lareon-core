<?php

namespace Lareon\Modules\Auth\App\Providers;

use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Cache\RateLimiting\Limit;
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
        $this->bootFeatures();
        $this->bootRateLimiters();
        $this->bootViews();
    }

    private function bootViews(): void
    {
        Fortify::loginView(fn() => View::first(['pages.auth.login', 'auth::authentication.pages.login']));
        Fortify::registerView(fn() => View::first(['pages.auth.register', 'auth::authentication.pages.register']));
        Fortify::verifyEmailView(fn() => View::first(['pages.auth.verify-email', 'auth::authentication.pages.verify-email']));
//        Fortify::requestPasswordResetLinkView(fn() => View::first(['pages.auth.forgot-password', 'lareon::authentication.pages.forgot-password']));
//        Fortify::resetPasswordView(fn() => View::first(['pages.auth.reset-password', 'lareon::authentication.pages.reset-password']));
        Fortify::twoFactorChallengeView(fn() => View::first(['pages.auth.2fa-challenge', 'auth::authentication.pages.pages.2fa-challenge']));
        Fortify::confirmPasswordView(fn() => View::first(['pages.auth.confirm-password', 'auth::authentication.pages.confirm-password']));

    }
    private function bootFeatures(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
//        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);
        Fortify::authenticateUsing($this->authenticationUser());


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


    private function bootRateLimiters(): void
    {
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
