<?php
namespace Lareon\Modules\Auth\App\Http\Requests\Auth\TwoFactorChallenge;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;

class TotpRequest extends FormRequest
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

        if ($this->code){
            $this->merge([
                'code' => implode($this->code),
            ]);
        }
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'nullable|string|size:6',
        ];
    }

    public function after(): array
    {
        return [
            fn(Validator $validator) => $this->validatingTotp($validator),
        ];
    }


    protected function validatingTotp(Validator $validator) :void
    {
        if ($validator->errors()->isNotEmpty()) return;

        if (!$this->hasValidCode()){
            $validator->errors()->add('code', __('The provided two factor authentication code was invalid.'));
            return;
        }

    }

    /**
     * Determine if the request has a valid two-factor code.
     *
     * @return bool
     */
    public function hasValidCode(): bool
    {
        return $this->code && tap(app(TwoFactorAuthenticationProvider::class)->verify(
                decrypt($this->challengedUser()->two_factor_secret), $this->input('code')
            ), function ($result) {
                if ($result) {
                    $this->session()->forget('login.id');
                }
            });
    }

}
