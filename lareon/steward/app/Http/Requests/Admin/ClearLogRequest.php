<?php

namespace Lareon\Steward\App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Lareon\Steward\App\Logics\LogLogic;

class ClearLogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('admin.setting.log.clear');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'   => ['required', 'string',],
        ];
    }

    public function after(): array
    {
        return [
            fn(Validator $validator) => $validator->validateFileName($validator),
        ];
    }

    private function validateFileName(Validator $validator): void
    {
        if ($validator->errors()->isNotEmpty()) return;

        $fileName = $this->request->get('name');
        $files = (new LogLogic())->getLogFiles()->result;
        if (in_array($fileName ,$files )){
            $validator->errors()->add('name', trans('lareon::errors.the_file_not_exist' ,['attribute' => $fileName]));
            return;
        }
    }
}
