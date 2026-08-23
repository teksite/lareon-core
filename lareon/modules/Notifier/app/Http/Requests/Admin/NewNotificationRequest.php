<?php
namespace Lareon\Modules\Notifier\App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class NewNotificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return userCan('admin.notification.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:100',
            'message' => 'required|string|max:500',
            'pinned' => 'sometimes|boolean',
            'roles' => 'sometimes|array',
            'roles.*' => 'exists:auth_roles,id'
        ];
    }
}
