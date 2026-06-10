<?php

namespace Lareon\Modules\Auth\App\Http\Requests\Api\Validations;

use Illuminate\Validation\Validator;
use Lareon\Modules\Auth\App\Services\OtpService;

trait ActionTokenRequestTrait
{
    /**
     * @param Validator $validator
     * @return void
     */
    protected function checkSentVerificationCode(Validator $validator): void
    {
        if ($validator->errors()->isNotEmpty()) return;

        $verificationService = new OtpService();

        $code= (string)$this->input('code');

        if ($verificationService::CODE_LENGTH !== strlen($code)) {
            $validator->errors()->add('credentials', trans('auth::messages.verification_code.invalid_auth_token'));
            return;
        }
        $isValid = $verificationService->verify($code, $this->contactValue, $this->actionType);

        if (!$isValid) {
            $validator->errors()->add('credentials', trans('auth::messages.verification_code.invalid_auth_token'));
            return;
        }

    }

    /**
     * @param Validator $validator
     * @param bool $testing
     * @return void
     */
    protected function getRetryTimeToSendCode(Validator $validator , bool $testing = false): void
    {
        if ($validator->errors()->isNotEmpty()) return;

        $retryTime = (new OtpService())->remainingTime($this->contactValue, $this->actionType, testing: $testing);

        if ($retryTime > 0) {
            $validator->errors()->add('credentials', trans('auth::messages.verification_code.wait_before_retry', ['seconds' => $retryTime]));
            return;
        }
    }
}
