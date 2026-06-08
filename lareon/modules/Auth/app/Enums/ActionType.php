<?php

namespace Lareon\Modules\Auth\App\Enums;

enum ActionType: string
{
    case REGISTER = 'register';
    case LOGIN = 'login';
    case RESET_PASSWORD = 'reset_password';
    case VERIFY = 'verify';
    case VERIFY_PHONE_OTP = 'verify_phone_otp';
    case VERIFY_EMAIL_OTP = 'verify_email_otp';

}
