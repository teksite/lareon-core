<?php

namespace Lareon\Modules\Questionnaire\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewSubmitRequest extends FormRequest
{
    use UseClientSideSubmit;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
