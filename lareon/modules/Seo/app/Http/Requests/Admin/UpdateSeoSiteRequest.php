<?php

namespace Lareon\Modules\Seo\App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use Lareon\Modules\Seo\App\Http\Requests\Admin\Helper\UseValidating;
use Lareon\Modules\Seo\App\Schema\SchemaOption;

class UpdateSeoSiteRequest extends FormRequest
{
    use UseValidating;
    public function authorize(): bool
    {
        return userCan('admin.seo.site.edit');
    }

    public function rules(): array
    {
        return [
            'seo' => ['required', 'array'],

            'state'   => ['required', 'array'],
            'state.*' => ['required', Rule::in(['0', '1'])],

            'seo.website.title' => [Rule::requiredIf(fn() => $this->boolean('state.website')), 'nullable', 'string', 'max:255',],

            'seo.website.description' => [Rule::requiredIf(fn() => $this->boolean('state.website')), 'nullable', 'string',],

            'seo.website.language' => ['nullable', 'string',],

            'seo.website.currency' => ['nullable', 'string',],

        ];
    }

    public function after(): array
    {
        return [
            fn(Validator $validator) => $this->validateWebsiteOptions($validator),
        ];
    }

    /**
     * Validate dynamic options.
     */
    private function validateWebsiteOptions(Validator $validator): void
    {
        if (!$this->boolean('state.website')) return;


        $this->validateInOptions(
            $validator,
            'seo.website.currency',
            SchemaOption::get('currency_list', [])
        );

        $this->validateInOptions(
            $validator,
            'seo.website.currency',
            SchemaOption::get('currency_list', [])
        );
    }

    /**
     * Validate select option.
     */
    private function validateInOptions(Validator $validator, string $field, array $options): void
    {
        $value = $this->input($field);

        if (blank($value)) return;

        if (!array_key_exists($value, $options)) {
            $validator->errors()->add($field, trans('validation.in', ['attribute' => str_replace('_', ' ', last(explode('.', $field))),]));
        }
    }
}
