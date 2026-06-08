<?php

namespace Lareon\Modules\Auth\App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Lareon\Modules\Auth\App\Actions\Otp\DetectContactType;
use Lareon\Modules\Auth\App\Enums\ContactType;
use Lareon\Modules\Auth\App\Enums\VerificationActionType;

class OtpService
{




    /**
     * @param string $to
     * @param VerificationActionType $action
     * @param bool $different
     * @param bool $testing
     * @return int|Carbon
     */
    public function getRetryTime(string $to, VerificationActionType $action, bool $different = true, bool $testing = false): int|Carbon
    {
        if (!self::PRODUCTION_MODE || $testing) return 0;
        $gateway = DetectContactType::handle($to);

        $cacheKey = $this->generateCacheKey($gateway, $action, $to);

        $cachedData = Cache::get($cacheKey);

        $reservedTime = $cachedData['expire_at'] ?? now();

        if (!$different) return $reservedTime;
        $diff = now()->diffInSeconds($reservedTime);
        return max($diff, 0);


    }


    public function verify($code, string $contact, VerificationActionType $action): bool
    {
        $isValid = $this->check($code, $contact, $action);
        if (!$isValid) return false;
        $this->forget($contact, $action);
        return true;
    }

    public function forget(string $contact, VerificationActionType $action): void
    {
        $gateway = DetectContactType::handle($contact);

        $cacheKey = $this->generateCacheKey($gateway, $action, $contact);

        Cache::tags(['otp', request()->ip(),])->forget($cacheKey);
    }

    public function flushCache(): void
    {
        Cache::tags(['otp', request()->ip()])->flush();
    }

}
