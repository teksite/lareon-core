<?php
namespace Lareon\Modules\Auth\App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOAuthRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return userCan('admin.setting.edit', 'admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'oauth'=>'required|array',
            'aouth.*.secret_key'=>'nullable|string',
            'aouth.*.client_id'=>'nullable|string',
        ];
    }
}
