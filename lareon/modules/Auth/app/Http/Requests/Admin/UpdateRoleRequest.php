<?php
namespace Lareon\Modules\Auth\App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Teksite\Authorize\Models\Role;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return userCan('admin.role.edit');

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return Role::rules('update' , $this->role->id);
    }
}
