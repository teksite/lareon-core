<?php

namespace Lareon\Modules\User\App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Lareon\Modules\User\App\Models\User;

class PasswordChangeRequest extends FormRequest
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
            'password' => ['required', 'string', 'min:6', 'confirmed', function ($attribute, $value, $fail) {
                if (!Hash::check($value, $this->user()->password)) {
                    return true;
                }
                return $fail(trans('lareon::errors.not_equal', ['attribute' => $attribute, 'other' => __('current password')]));
            }],
            'password_confirmation' => 'required|string',
            'current_password'      => 'required|string|min:6',

        ];
    }
}
