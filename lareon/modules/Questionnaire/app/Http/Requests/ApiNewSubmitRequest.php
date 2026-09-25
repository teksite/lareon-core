<?php
namespace Lareon\Modules\Questionnaire\App\Http\Requests;

use Teksite\Module\Foundations\ApiFormRequest;

class ApiNewSubmitRequest extends ApiFormRequest
{
    use UseClientSideSubmit;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->ajax();
    }
}
