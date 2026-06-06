<?php

namespace Lareon\Modules\Auth\App\Http\Requests\Auth\OTP;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class VerifyVerificationCodeRequest extends BaseAuthRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'contact' => ['bail', 'required', 'string', 'min:5', 'max:100', new ContactCheckRule],
            'action'  => ['bail', 'required', 'string', Rule::enum(VerificationActionType::class)],
            'code'    => ['bail', 'required', 'string'],
        ];
    }

    public function after(): array
    {
        return [
            fn(Validator $validator) => $this->appendContactData($validator),
            fn(Validator $validator) => $this->checkExistenceContactCondition($validator),
            fn(Validator $validator) => $this->checkSentVerificationCode($validator),
        ];
    }
}
