<?php

namespace Lareon\Modules\Auth\App\Http\Requests\Api\Validations;

use Illuminate\Validation\Validator;
use Lareon\Modules\Auth\App\Services\ActionTokenService;

trait TokenCodeRequestTrait
{
    /**
     * @param Validator $validator
     * @return void
     */
    public function checkToken(Validator $validator): void
    {
        if ($validator->errors()->isNotEmpty()) return;

        $tokenService = new ActionTokenService();
        if (!$tokenService->verify($this->input('token' , ''), $this->contactValue, $this->actionType)) {
            $validator->errors()->add('credentials', trans('auth::messages.auth.invalid_token'));
            return;
        }


    }
}
