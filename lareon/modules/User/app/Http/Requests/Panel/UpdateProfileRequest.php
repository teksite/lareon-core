<?php
namespace Lareon\Modules\User\App\Http\Requests\Panel;

use Illuminate\Foundation\Http\FormRequest;
use Lareon\Modules\User\App\Models\User;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return userCan('panel.profile.edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return  User::rules('update' , auth()->id());
    }
}
