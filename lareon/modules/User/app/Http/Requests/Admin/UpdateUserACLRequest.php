<?php

namespace Lareon\Modules\User\App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Lareon\Modules\User\App\Models\User;

class UpdateUserACLRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return userCan('admin.user.acl.edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge(
            [
                'roles'   => 'required|array',
                'roles.*' => 'exists:auth_roles,id|required',
            ], [
                'permissions'   => 'nullable|array',
                'permissions.*' => 'nullable|exists:auth_permissions,id',
            ]
        );
    }
}
