<?php

namespace Lareon\Modules\Meta\App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Lareon\Modules\Meta\App\Models\MetaTemplate;

class NewTemplateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return userCan('admin.meta.template.create');
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return MetaTemplate::rules('create');
    }


    protected function prepareForValidation()
    {
        $modifiedTemplate = $this->modifyTemplate();

        return $this->merge([
            'model_type' => $modifiedTemplate['model_type'],
            'template'   => $modifiedTemplate['template'],
        ]);
    }

    protected function modifyTemplate(): array
    {

        $raw = request()->input('template');

        $rawArray = explode('|', $raw);

        return [
            'model_type' => $rawArray[0],
            'template'   => $rawArray[1],
        ];
    }
}
