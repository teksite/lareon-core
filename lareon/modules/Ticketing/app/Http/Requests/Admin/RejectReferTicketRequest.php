<?php

namespace Lareon\Modules\Ticketing\App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RejectReferTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return userCan('admin.ticket.assign.reject', 'admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'decision_reason' => 'required|string|min:5|max:2000',
            'next_admin_id'   => 'required|integer|exists:users_admins,id',
        ];
    }

    public function attributes(): array
    {
        return [
            'decision_reason' => __('decision reason'),
            'next_admin_id'   => __("next admin id"),
        ];
    }
}
