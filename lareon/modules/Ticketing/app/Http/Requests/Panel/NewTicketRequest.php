<?php
namespace Lareon\Modules\Ticketing\App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Lareon\Modules\Ticketing\App\Enums\PriorityType;
use Lareon\Modules\Ticketing\App\Http\Requests\Concerns\ValidatesAttachments;

class NewTicketRequest extends FormRequest
{
    use ValidatesAttachments;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return userCan('panel.ticket.create' ,'web');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'subject' => 'required|string|max:190',
            'priority' => ['required', new Enum(PriorityType::class)],
            'body' => 'required|string|min:5|max:10000',
            ...$this->attachmentRules()
        ];
    }


}
