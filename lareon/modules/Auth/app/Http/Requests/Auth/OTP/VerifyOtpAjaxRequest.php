<?php

namespace Lareon\Modules\Auth\App\Http\Requests\Auth\OTP;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Lareon\Modules\Auth\App\Enums\ContactType;
use Lareon\Modules\Auth\App\Enums\VerificationActionType;
use Lareon\Modules\Auth\App\Services\OtpService;
use Lareon\Modules\User\App\Models\User;
use Teksite\Extralaravel\Http\ApiFormRequest;

class VerifyOtpAjaxRequest extends  FormRequest
{

    public ?User $user = null;
    public ?ContactType $contactType = null;
    public ?string $contact = null;
    public ?VerificationActionType $actionType = null;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {

        if ($this->otp_code){
            $this->replace([
                'otp_code' => implode($this->otp_code),
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
        dd($this->toArray());
        return [
            'contactType' => ['bail', 'required', 'string', Rule::enum(ContactType::class)],
            'action'  => ['bail', 'required', 'string', Rule::enum(VerificationActionType::class)],
            'otp_code'    => ['bail', 'required', 'string'],
        ];
    }
    public function after(): array
    {
        return [
            fn(Validator $validator) => $this->resolveUser($validator),
            fn(Validator $validator) => $this->resolveContact($validator),
            fn(Validator $validator) => $this->validateCode($validator),
        ];
    }

    private function resolveUser(Validator $validator): void
    {
        if ($validator->errors()->isNotEmpty()) return;

        $userId = $this->session()->get('login.id');


        if (!$userId || !$user = User::find($userId)) {
            $validator->errors()->add('credentials', trans('auth::messages.auth.user_not_found'));
            return;
        }

        $this->user = $user;
    }

    private function resolveContact(Validator $validator): void
    {
        if ($validator->errors()->isNotEmpty()) return;

        $contactType = ContactType::from($this->string('contactType'));

        $contact = match ($contactType) {
            ContactType::EMAIL => $this->user->email,
            ContactType::PHONE => $this->user->phone,
        };

        $actionType = VerificationActionType::from($this->string('action'));


        if (is_null($contact)) {
            $validator->errors()->add('credentials', trans('auth::messages.auth.contact_failed'));
            return;
        }

        $this->contactType = $contactType;
        $this->contact = $contact;
        $this->actionType = $actionType;
    }

    private function validateCode(Validator $validator): void
    {
        if ($validator->errors()->isNotEmpty()) return;
        $verificationService = new OtpService();

        if ($verificationService::CODE_LENGTH !== strlen((string)$this->input('otp_code'))) {
            $validator->errors()->add('credentials', trans('auth::messages.verification_code.not_valid'));
        }

        $isValid = $verificationService->verify($this->input('code'), $this->contact, VerificationActionType::tryFrom($this->actionType));


        if (!$isValid) {
            $validator->errors()->add('credentials', trans('auth::messages.verification_code.not_valid'));
            return;
        }

    }

}
