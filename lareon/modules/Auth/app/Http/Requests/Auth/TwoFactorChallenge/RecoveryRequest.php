<?php

namespace Lareon\Modules\Auth\App\Http\Requests\Auth\TwoFactorChallenge;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class RecoveryRequest extends FormRequest
{
    use TwoFactorFormRequestTrait;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return !!auth()->guest();
    }


    protected function prepareForValidation(): void
    {
        $this->challengedUser();
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'recovery_code' => 'bail|string|required',
        ];
    }

    public function after(): array
    {
        return [
            fn(Validator $validator) => $this->validatingRecoveryCode($validator),
        ];
    }


    protected function validatingRecoveryCode(Validator $validator): void
    {
        if ($validator->errors()->isNotEmpty()) return;

        if (!$this->validRecoveryCode()) {
            $validator->errors()->add('recovery', __('The provided two factor authentication code was invalid.'));
            return;
        }

    }

    /**
     * Determine if the request has a valid two-factor code.
     *
     * @return bool
     */
    public function validRecoveryCode(): bool
    {
        return tap(collect($this->challengedUser()->recoveryCodes())->first(function ($code) {
            return hash_equals($code, $this->input('recovery_code')) ? $code : null;
        }), function ($code) {
            if ($code) {
                $this->session()->forget('login.id');
            }
        });
    }


}
