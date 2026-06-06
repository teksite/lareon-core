<?php

namespace Lareon\Modules\Auth\App\Http\Requests\Auth\OTP;

use Illuminate\Foundation\Http\FormRequest;
use Lareon\Modules\Auth\App\Http\Requests\Auth\OTP\Afters\AuthDataRequestTrait;
use Lareon\Modules\Auth\App\Http\Requests\Auth\OTP\Afters\PasswordRequestTrait;
use Lareon\Modules\Auth\App\Http\Requests\Auth\OTP\Afters\TokenCodeRequestTrait;
use Lareon\Modules\Auth\App\Http\Requests\Auth\OTP\Afters\VerificationCodeRequestTrait;
use Teksite\Extralaravel\Http\ApiFormRequest;


class BaseAuthRequest extends FormRequest
{
    use VerificationCodeRequestTrait , TokenCodeRequestTrait , AuthDataRequestTrait ,PasswordRequestTrait;

}
