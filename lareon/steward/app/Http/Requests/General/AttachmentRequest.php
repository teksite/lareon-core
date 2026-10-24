<?php
namespace Lareon\Steward\App\Http\Requests\General;

use Illuminate\Foundation\Http\FormRequest;

class AttachmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'disk'=>'required|string|max:50',
            'path'=>'required|string',
            'signature'=>'nullable|string',
            'expires'=>'nullable|string',
            'filename'=>'nullable|string',
        ];
    }
}
