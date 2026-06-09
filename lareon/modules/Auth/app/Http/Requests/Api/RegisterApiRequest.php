<?php
namespace Lareon\Modules\Auth\App\Http\Requests\Api;

use Illuminate\Validation\Rule;
use Lareon\Modules\Auth\App\Enums\ActionType;
use Teksite\Module\Foundations\ApiFormRequest;

class RegisterApiRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return !auth('sanctum')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'action'  => ['bail', 'required', 'string', Rule::enum(ActionType::class)],
            'contact' => ['bail', 'required', 'string', 'min:5', 'max:100',],
            'contact_alt' => ['bail', 'required', 'string', 'min:5', 'max:100',],
            'password'    => ['bail', 'required', 'string','confirmed' ,'min:5', 'max:20'],
            'name'    => ['bail', 'required', 'string'],
            'token' => ['bail', 'required', 'string', 'min:5', 'max:100'],
        ];
    }
}
