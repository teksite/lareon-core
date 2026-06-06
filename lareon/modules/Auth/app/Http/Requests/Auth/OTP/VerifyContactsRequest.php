<?php

namespace Lareon\Modules\Auth\App\Http\Requests\Auth\OTP;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class VerifyContactsRequest extends BaseAuthRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('sanctum')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'contact' => ['bail', 'required', 'string', new ContactCheckRule],
            'action'  => ['bail', 'required', 'string', Rule::enum(VerificationActionType::class)],
            'code'    => ['bail', 'required', 'string'],        ];
    }

    public function after(): array
    {
        return [
            fn(Validator $validator) => $this->appendContactData($validator),
            fn(Validator $validator) => $this->checkSentVerificationCode($validator),
            fn(Validator $validator) => $this->checkIfContactIsNull($validator),
        ];
    }


}
