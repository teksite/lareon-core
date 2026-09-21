<?php
namespace Lareon\Steward\App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Lareon\Steward\App\Models\Admin;

class UpdateAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return userCan('admin.admin.edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return Admin::rules('update' ,$this->admin->id);

    }
}
