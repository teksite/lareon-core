<?php
namespace Lareon\Modules\Auth\App\Http\Requests\Api;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Lareon\Modules\Auth\App\Enums\ActionType;

class VerifyVerificationCodeApiRequest extends BaseApiRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'contact' => ['bail', 'required', 'string', 'min:5', 'max:100'],
            'action'  => ['bail', 'required', 'string', Rule::enum(ActionType::class)],
            'code'    => ['bail', 'required', 'string'],
        ];
    }


    public function after(): array
    {
        return [
            fn(Validator $validator) => $this->resolveContactData($validator),
            fn(Validator $validator) => $this->resolveUser($validator),
            fn(Validator $validator) => $this->checkExistenceContactCondition($validator),
            fn(Validator $validator) => $this->checkSentVerificationCode($validator),
        ];
    }
}
