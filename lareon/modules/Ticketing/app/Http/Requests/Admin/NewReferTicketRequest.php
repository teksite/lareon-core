<?php

namespace Lareon\Modules\Ticketing\App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NewReferTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return userCan('admin.ticket.assign.call', 'admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'to_admin_id' => ['required', 'integer', Rule::exists('users_admins', 'id'), 'different:current_admin_id'],
            'reason'      => 'required|string|min:5|max:2000',
        ];
    }


    public function attributes(): array
    {
        return [
            'to_admin_id' => __('referred admin'),
            'reason'      => __('reason'),
        ];
    }
}
