<?php

namespace Lareon\Modules\Auth\App\Http\Requests\Auth\OTP;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ForgotPasswordRequest extends BaseAuthRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return !auth('sanctum')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'contact' => ['bail', 'required', 'string', 'min:5', 'max:100', new ContactCheckRule],
            'action'  => ['bail', 'required', 'string', Rule::enum(VerificationActionType::class)],
            'token' => ['bail', 'required', 'string', 'min:5', 'max:100'],
            'password'    => ['bail', 'required', 'string','confirmed' ,'min:5', 'max:20'],

        ];
    }

    public function after(): array
    {
        return [
            fn(Validator $validator) => $this->appendContactData($validator),
            fn(Validator $validator) => $this->checkToken($validator),
        ];
    }

}
