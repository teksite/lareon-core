<?php
namespace Lareon\Modules\Auth\App\Http\Requests\Api;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Lareon\Modules\Auth\App\Enums\ActionType;
use Modules\Auth\Enums\VerificationActionType;
use Modules\Auth\Rules\ContactCheckRule;
use Teksite\Module\Foundations\ApiFormRequest;

class LoginApiRequest extends BaseApiRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'contact'  => ['bail', 'required', 'string', 'min:5', 'max:100'],
            'action'  => ['bail', 'required', 'string', Rule::enum(ActionType::class)],
            'password' => ['bail', 'required_without:token'],
            'token'    => ['bail', 'required_without:password'],
        ];
    }



    public function after(): array
    {
        return [
            fn(Validator $validator) => $this->resolveContactData($validator),
            fn(Validator $validator) => $this->resolveUser($validator),
            fn(Validator $validator) => $this->checkPasswordOrVerificationToken($validator),
        ];
    }


    private function checkPasswordOrVerificationToken(Validator $validator): void
    {
        $verificationToken = $this->input('token');
        $password = $this->input('password');

        if (is_null($verificationToken) && $password) {
            $this->verifyPassword($validator);
            return;
        } elseif (is_null($password) && $verificationToken) {
            $this->checkToken($validator);
            return;
        }

        $validator->errors()->add('credential', trans('auth::messages.auth.password_and_code_conflict'));
        return;
    }
}
