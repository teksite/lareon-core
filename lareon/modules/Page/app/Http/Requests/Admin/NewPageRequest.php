<?php
namespace Lareon\Modules\Page\App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Lareon\Modules\Page\App\Models\Page;

class NewPageRequest extends FormRequest
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
        return Page::rules('create');
    }
}
