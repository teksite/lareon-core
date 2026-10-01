<?php

namespace Lareon\Modules\Auth\App\Http\Controllers\Web\Auth\OAuths;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Lareon\Modules\Auth\App\Http\Controllers\Controller;
use Lareon\Modules\User\App\Logics\UserLogic;
use Lareon\Modules\User\App\Models\User;
use Teksite\Handler\Enums\ResponseType;

class OAuthsController extends Controller
{
    public function redirect(string $provider): RedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * @throws \Throwable
     */
    public function callback(string $provider)
    {
        try {
            $social = Socialite::driver($provider)->user();
            if (!$social->getEmail()) return redirect()->route('login')->withErrors(['oauth' => trans('validation.email', ['attribute' => 'email'])]);
            $user = User::query()->firstWhere('email', $social->getEmail()) ?? $this->createUser($social);

            if (!$user->hasVerifiedEmail()) $user->markEmailAsVerified();

            auth()->login($user, remember: true);

            request()->session()->regenerate();

            return $this->redirecting(
                route('panel.dashboard'),
                __('successfully done'),
                ResponseType::SUCCESS,
                200,
            );
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            return $this->redirecting(
                route('login'),
                __('something went wrong'),
                ResponseType::FAILED,
                500,
            );
        }
    }

    /**
     * @throws \Throwable
     */
    private function createUser($social): User
    {
        return (new UserLogic())->create([
            'name'     => $social->getName() ?: $social->getNickname(),
            'email'    => $social->getEmail(),
            'phone'    => 989126037212,
            'password' => Str::random(32),
        ])->result;
    }


    private function redirecting(string $url, string $message, ResponseType $responseType, int $statusCode, mixed $error = null)
    {
        return redirect()
            ->to($url)
            ->with(['reply' =>
                        [
                            'message'    => $message,
                            'statusCode' => $statusCode,
                            'type'       => $responseType->value,
                            'error'      => $error,
                        ],
            ]);
    }
}
