<?php

namespace Lareon\Modules\Ticketing\App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Lareon\Modules\Ticketing\App\Enums\PriorityType;

class ReplyTicketMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return userCan('panel.ticket.reply', 'web');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'body'     => 'required|string|min:5|max:10000',
            // ُTODO atachement
            // 'attachments' => $this->attachmentRules(),
            // 'attachments.*' => $this->attachmentFileRules(),
        ];
    }
}
