<?php
namespace Lareon\Modules\Ticketing\App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ReplyTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return userCan('admin.ticket.reply', 'admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'body'=>'required|min:3|max:10000',
            'is_internal_note'=>'sometimes|boolean',
        ];
    }
}
