<?php
namespace Lareon\Modules\Seo\App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

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
            'website'=>'required|array',
            'website.title'=>'required|string',
            'website.description'=>'required|string',
            'website.currency'=>'required|string',
            'website.language'=>'required|string',
        ];
    }
}
