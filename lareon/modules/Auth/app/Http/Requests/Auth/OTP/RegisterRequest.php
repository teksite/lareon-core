<?php

namespace Lareon\Modules\Auth\App\Http\Requests\Auth\OTP;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;


class RegisterRequest extends BaseAuthRequest
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
            'action'  => ['bail', 'required', 'string', Rule::enum(VerificationActionType::class)],
            'contact' => ['bail', 'required', 'string', 'min:5', 'max:100', new ContactCheckRule],
            'contact_alt' => ['bail', 'required', 'string', 'min:5', 'max:100', new ContactCheckRule],
            'password'    => ['bail', 'required', 'string','confirmed' ,'min:5', 'max:20'],
            'name'    => ['bail', 'required', 'string'],
            'token' => ['bail', 'required', 'string', 'min:5', 'max:100'],
        ];
    }

    public function after(): array
    {
        return [
            fn(Validator $validator) => $this->appendContactData($validator),
            fn(Validator $validator) => $this->appendAltContactData($validator),
            fn(Validator $validator) => $this->checkToken($validator),
        ];
    }

}
