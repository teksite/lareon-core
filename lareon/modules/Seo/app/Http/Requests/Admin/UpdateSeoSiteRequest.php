<?php

namespace Lareon\Modules\Seo\App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateSeoSiteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return userCan('admin.seo.site.edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'seo' => 'required|array',
        ];
    }

    public function after()
    {
        return [
            fn(Validator $validator) => $this->validateWebsiteSeo($validator),
        ];
    }

    private function validateWebsiteSeo(Validator $validator): void
    {
        if ($validator->errors()->isNotEmpty()) return;
        $website = $this->input('seo.website', []);

        if (empty($website)) return;

        $state = $website['state'] ?? false;

        if (!$state) return;

        $data = $website['data'] ?? [];

        $title= $data['title'] ?? null;
        $description= $data['description'] ?? null;
        $language= $data['language'] ?? null;
        $currency= $data['currency'] ?? null;

        if (is_null($title)){
            $this->validator->errors()->add('seo.website.title', trans('validation.required', ['attribute' => 'title']));
            return;
        }

        if (is_null($description)){
            $this->validator->errors()->add('seo.website.description', trans('validation.required', ['attribute' => 'description']));
            return;
        }


        if (is_null($language) || !in_array($language , array_keys(\Teksite\Extralaravel\Enums\Langs::cases()))){
            $this->validator->errors()->add('seo.website.description', trans('validation.not_in', ['attribute' => 'language']));
            return;
        }


        if (is_null($currency) || !in_array($currency , array_keys(\Teksite\Extralaravel\Enums\Currencies::cases()))){
            $this->validator->errors()->add('seo.website.description', trans('validation.required', ['attribute' => 'currency']));
            return;
        }
    }


}
