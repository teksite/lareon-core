<?php

namespace Lareon\Modules\Auth\App\Enums;

use Lareon\Modules\Auth\App\Actions\Otp\DetectContactType;

enum ContactType :string
{
    case EMAIL = 'email';
    case PHONE = 'phone';

    public static function getType(int|string $username): ?ContactType
    {
        return DetectContactType::handle($username);
    }
}
