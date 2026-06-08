<?php

namespace Lareon\Modules\Auth\App\Enums;

use Teksite\Extralaravel\Enums\MobilePatterns;

enum ContactType: string
{

    case EMAIL = 'email';
    case PHONE = 'phone';

        public static function detect(string $value): ?self
    {
        $value = trim($value);

        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return self::EMAIL;
        }

        $normalized = MobilePatterns::normalize($value);

        if (self::isPhone($normalized)) {
            return self::PHONE;
        }

        return null;
    }

        /**
         * check if value is phone-like
         */
        private static function isPhone(string $value): bool
    {
        if (!preg_match('/^\+?\d+$/', $value))    return false;

        if (str_starts_with($value, '+') || str_starts_with($value, '00')) {
            return MobilePatterns::detectCountry($value) !== null;
        }
        return strlen($value) >= 8 && strlen($value) <= 15;
    }
}
