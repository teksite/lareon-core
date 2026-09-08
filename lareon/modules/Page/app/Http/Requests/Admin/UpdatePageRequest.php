<?php

namespace Lareon\Modules\Page\App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Lareon\Modules\Page\App\Models\Page;
use Lareon\Modules\Seo\App\Models\SeoMetaModel;
use Lareon\Modules\Seo\App\Models\SeoSchemaModel;
use Lareon\Modules\Seo\App\Models\SeoSitemap;

class UpdatePageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return userCan('admin.user.edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge(
            Page::rules('update', $this->page->id),
            Page::seoRules()
        );
    }
}
