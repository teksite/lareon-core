<?php

namespace Lareon\Modules\Auth\App\Http\Requests\Auth\OTP;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Laravel\Fortify\Contracts\FailedTwoFactorLoginResponse;
use Lareon\Modules\Auth\App\Enums\ContactType;
use Lareon\Modules\Auth\App\Enums\VerificationActionType;
use Lareon\Modules\User\App\Models\User;
use Teksite\Extralaravel\Http\ApiFormRequest;

class SendOtpAjaxRequest extends ApiFormRequest
{

    public Authenticatable|User|null $user=null;

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
            'contactType' => ['bail', 'required', 'string', Rule::enum(ContactType::class)],
            'action' => ['bail', 'required', 'string', Rule::enum(VerificationActionType::class)],
        ];
    }

    public function after(): array
    {
        return [
            fn(Validator $validator) => $this->validateUser($validator),
        ];
    }

    private function validateUser(Validator $validator): void
    {
        if ($validator->errors()->isNotEmpty()) return;
        if ($this->user) {
            return;
        }
        if (!$this->session()->has('login.id') || !$user = User::find($this->session()->get('login.id'))) {
            throw new HttpResponseException(
                app(FailedTwoFactorLoginResponse::class)->toResponse($this)
            );
        }
        $this->user = $user;
    }

}
