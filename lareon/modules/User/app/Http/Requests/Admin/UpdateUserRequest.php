<?php
namespace Lareon\Modules\User\App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Lareon\Modules\User\App\Models\User;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return userCan('admin.user.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge(
            User::rules('update' , $this->user->id),
            [
                'email_verified_at' => 'sometimes',
                'phone_verified_at' => 'sometimes',
            ], [
                'send_email_notification' => 'sometimes|in:-1,0,1',
                'send_phone_notification' => 'sometimes|in:-1,0,1',
            ]
        );
    }
}
