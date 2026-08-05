<?php

namespace Lareon\Modules\Seo\App\Http\Requests\Admin\Helper;

use Illuminate\Validation\Validator;
use Lareon\Modules\Seo\App\Schema\SchemaOption;

trait UseValidating
{


    protected function validateContactPoint(Validator $validator, string $path): void
    {

        $this->validateNode($validator, $path, function (Validator $validator, array $contact, string $path) {

            if (blank($contact['telephone'] ?? null)) {
                $validator->errors()->add(
                    "{$path}.telephone",
                    trans('validation.required', ['attribute' => __('telephone')]));
            }

            if (blank($contact['contactType'] ?? null)) {
                $validator->errors()->add(
                    "{$path}.contactType",
                    trans('validation.required', ['attribute' => __('contact type')])
                );
            }

            if (!empty($contact['availableLanguage'])) {

                $this->validateArrayInOptions(
                    $validator,
                    "{$path}.availableLanguage",
                    $contact['availableLanguage'],
                    SchemaOption::get('language_list'),
                    __('available language')
                );
            }

            if (!empty($contact['areaServed'])) {

                $this->validateArrayInOptions(
                    $validator,
                    "{$path}.areaServed",
                    $contact['areaServed'],
                    SchemaOption::get('country_list'),
                    __('area served')
                );
            }
        }
        );
    }

    protected function validateNode(Validator $validator, string $path, callable $callback): void
    {
        $data = data_get($this->all(), $path);

        if (blank($data)) return;

        if (array_is_list($data)) {
            foreach ($data as $index => $item) {
                $callback($validator, $item, "{$path}.{$index}");
            }
            return;
        }

        $callback($validator, $data, $path);
    }
    protected function validateArrayInOptions(Validator $validator, string $field, mixed $values, array $options, ?string $attribute = null,): void {

        if (blank($values))return;

        $allowed = array_keys($options);

        $isArray = is_array($values);

        foreach ((array) $values as $index => $value) {

            if (blank($value)) continue;

            if (! in_array($value, $allowed, true)) {

                $validator->errors()->add(
                    $isArray ? "{$field}.{$index}" : $field,
                    trans('validation.in', ['attribute' => $attribute ?? $field,])
                );
            }
        }
    }
}
