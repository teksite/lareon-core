<?php

namespace Lareon\Modules\Auth\App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class RateLimiterServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->configureRateLimiting();
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('auth.check-user', fn(Request $request) => [
            Limit::perMinute(10)->by('check-user:ip:'.$request->ip())->response($this->tooManyAttempts()),
            Limit::perHour(60)->by('check-user:ip-hour:'.$request->ip())->response($this->tooManyAttempts()),
        ]);

        RateLimiter::for('auth.verification-send', fn(Request $request) => [
            Limit::perMinute(1)->by('vc-send:min:'.$this->identifierKey($request))->response($this->tooManyAttempts()),
            Limit::perHour(5)->by('vc-send:hour:'.$this->identifier($request))->response($this->tooManyAttempts()),
            Limit::perDay(20)->by('vc-send:ip-day:'.$request->ip())->response($this->tooManyAttempts()),
        ]);

        RateLimiter::for('auth.verification-verify', fn(Request $request) => [
            Limit::perMinute(5)->by('vc-verify:min:'.$this->identifierKey($request))->response($this->tooManyAttempts()),
            Limit::perHour(15)->by('vc-verify:hour:'.$this->identifier($request))->response($this->tooManyAttempts()),
        ]);

        RateLimiter::for('auth.register', fn(Request $request) => [
            Limit::perMinute(3)->by('register:min:'.$request->ip())->response($this->tooManyAttempts()),
            Limit::perHour(10)->by('register:hour:'.$request->ip())->response($this->tooManyAttempts()),
        ]);

        RateLimiter::for('auth.login', fn(Request $request) => [
            Limit::perMinute(5)->by('login:'.$this->identifierKey($request))->response($this->tooManyAttempts()),
            Limit::perMinute(20)->by('login:ip:'.$request->ip())->response($this->tooManyAttempts()),
        ]);

        RateLimiter::for('auth.forgot-password', fn(Request $request) => [
            Limit::perHour(5)->by('forgot:'.$this->identifierKey($request))->response($this->tooManyAttempts()),
            Limit::perHour(20)->by('forgot:ip:'.$request->ip())->response($this->tooManyAttempts()),
        ]);

        RateLimiter::for('auth.verify-contact', fn(Request $request) => [
            Limit::perMinute(5)->by('verify-contact:'.$this->userOrIp($request))->response($this->tooManyAttempts()),
            Limit::perHour(20)->by('verify-contact:hour:'.$this->userOrIp($request))->response($this->tooManyAttempts()),
        ]);

        RateLimiter::for('auth.session', fn(Request $request) => Limit::perMinute(60)->by('session:'.$this->userOrIp($request))->response($this->tooManyAttempts()));
    }

    /** شناسه کاربر (موبایل/ایمیل/...) نرمال‌شده */
    protected function identifier(Request $request): string
    {
        $value = $request->input('mobile')
            ?? $request->input('email')
            ?? $request->input('phone')
            ?? $request->input('contact')
            ?? $request->input('username')
            ?? 'unknown';

        return mb_strtolower(trim((string)$value));
    }

    /** شناسه + IP (برای جلوگیری از قفل کردن حساب دیگران توسط مهاجم) */
    protected function identifierKey(Request $request): string
    {
        return $this->identifier($request).'|'.$request->ip();
    }

    protected function userOrIp(Request $request): string
    {
        return (string)($request->user()?->getAuthIdentifier() ?? $request->ip());
    }

    protected function tooManyAttempts(): \Closure
    {
        return fn(Request $request, array $headers) => response()->json([
            'message'     => 'تعداد درخواست‌ها بیش از حد مجاز است. لطفاً بعداً تلاش کنید.',
            'retry_after' => $headers['Retry-After'] ?? null,
        ], 429, $headers);
    }
}
