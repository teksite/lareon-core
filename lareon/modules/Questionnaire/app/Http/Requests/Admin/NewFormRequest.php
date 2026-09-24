<?php
namespace Lareon\Modules\Questionnaire\App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Lareon\Modules\Questionnaire\App\Models\Form;
use Lareon\Modules\Questionnaire\App\Models\FormAnnouncement;
use Lareon\Modules\Questionnaire\App\Models\FormRule;

class NewFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return userCan('admin.questionnaire.form.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge(Form::rules(),FormAnnouncement::rulesForModels(), FormRule::rulesForModels());

    }
}
