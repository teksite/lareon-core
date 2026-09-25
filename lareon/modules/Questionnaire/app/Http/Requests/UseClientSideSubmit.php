<?php

namespace Lareon\Modules\Questionnaire\App\Http\Requests;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Crypt;
use Lareon\Modules\Questionnaire\App\Models\Form;
use Lareon\Modules\Questionnaire\App\Models\FormInbox;

trait UseClientSideSubmit
{

    protected ?Form $form = null;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $this->loadForm();
        return array_merge(
            FormInbox::rulesForModels(),
            $this->rulesOfTheForm($this->form->validationRules->rules ?? []),

        //TODO add recaptcha ['g-recaptcha-response' => new CaptchaRule()]
        );
    }

    /**
     * Get the validated data and append server-side data.
     */
    public function validated($key = null, $default = null,): mixed
    {
        $validated = parent::validated();

        $validated['data_info']['ip_address'] = $this->ip();
        $validated['data_info']['form'] = $this->form;

        if ($key !== null) return data_get($validated, $key, $default);

        return $validated;
    }

    protected function passedValidation(): void
    {
        $this->merge(['form' => $this->form, 'ip_address' => $this->ip(),]);
    }

    /**
     * Load and decrypt form identify value.
     */
    protected function loadForm(): void
    {
        $identify = $this->input('data_info.identify');
        if (!$identify) abort(403, 'Suspicious behavior');

        try {
            $formId = Crypt::decrypt($identify);
            $this->form = Form::findOrFail($formId);
        } catch (DecryptException $e) {
            abort(403, 'Invalid or tampered form.');
        } catch (\Throwable $e) {
            abort(404, 'Form not found or no longer available.');
        }
    }

    /**
     * Convert dynamic rule array to associative validation rules.
     *
     * @param array<int, array<string, mixed>> $formRuleArray
     * @return array<string, ValidationRule|array|string>
     */
    protected function rulesOfTheForm(array $formRuleArray,): array
    {
        return collect($formRuleArray)
            ->mapWithKeys(fn($item,) => [$item['field'] => $item['rules']])
            ->toArray();
    }
}
