<?php

namespace Lareon\Modules\Notifier\App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Lareon\Modules\Notifier\App\Enums\ChannelsEnum;
use Lareon\Modules\Notifier\App\Enums\NotificationEnum;

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
            'title'      => 'required|string|max:100',
            'message'    => 'required|string|max:500',
            'type'       => ['required', Rule::enum(NotificationEnum::class)],
            'channels'   => ['nullable', 'array'],
            'channels.*' => ['string', Rule::enum(ChannelsEnum::class)],
            'roles'      => 'nullable|array|required_without:users',
            'roles.*'    => 'exists:auth_roles,id',
            'users'      => 'nullable|array|required_without:roles',
            'users.*'    => 'exists:users,id',
        ];

    }
}
