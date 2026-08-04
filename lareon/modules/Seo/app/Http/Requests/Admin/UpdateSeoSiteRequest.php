<?php

namespace Lareon\Modules\Seo\App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Teksite\Extralaravel\Enums\Langs;
use Teksite\Extralaravel\Enums\Currencies;

class UpdateSeoSiteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return userCan('admin.seo.site.edit');
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'seo' => 'required|array',
        ];
    }

    public function after(): array
    {
        return [
            fn(Validator $validator) => $this->validateWebsiteSeo($validator),
        ];
    }

    private function validateWebsiteSeo(Validator $validator): void
    {
        if ($validator->errors()->isNotEmpty()) return;

        $website = $this->input('seo.website', []);

        if (empty($website) || !($website['state'] ?? false)) return;

        $data = $website['data'] ?? [];

        $this->validateRequired($validator, 'seo.website.title', $data['title'] ?? null, 'title');
        $this->validateRequired($validator, 'seo.website.description', $data['description'] ?? null, 'description');
        $this->validateEnum($validator, 'seo.website.language', $data['language'] ?? null, 'language', Langs::class);
        $this->validateEnum($validator, 'seo.website.currency', $data['currency'] ?? null, 'currency', Currencies::class);
    }

    private function validateRequired(Validator $validator, string $field, mixed $value, string $attribute): void
    {
        if (is_null($value) || $value === '') {
            $validator->errors()->add($field, trans('validation.required', ['attribute' => $attribute]));
        }
    }

    /**
     * @param class-string<\UnitEnum> $enumClass
     */
    private function validateEnum(Validator $validator, string $field, mixed $value, string $attribute, string $enumClass): void
    {
        if (is_null($value) || $value === '') {
            $validator->errors()->add($field, trans('validation.required', ['attribute' => $attribute]));
            return;
        }

        $validNames = collect($enumClass::cases())->pluck('name')->toArray();

        if (!in_array($value, $validNames, true)) {
            $validator->errors()->add($field, trans('validation.not_in', ['attribute' => $attribute]));
        }
    }
}
