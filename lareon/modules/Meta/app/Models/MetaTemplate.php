<?php

namespace Lareon\Modules\Meta\App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\Rule;

#[Fillable(['title', 'template', 'model_type'])]
#[Table('meta_templates')]
class MetaTemplate extends Model
{


    public static function rules(string $operation, int|null $id = null): array
    {
        return match (true) {
            $operation === 'create'          => [
                'title'      => 'required|string|max:100|unique:meta_templates,title',
                'template'   => 'required|string|max:100|unique:meta_templates,template',
                'model_type' => ['required', 'string', 'max:100', Rule::in(array_keys(config('meta.models')))],
            ],
            ($operation === 'update' && $id) => [
                'title'    => ['required', 'string', Rule::unique('meta_templates', 'title')->ignore($id)],
                'elements' => 'nullable|array',
//                'template'  => ['required', 'string', Rule::unique('meta_templates', 'template')->ignore($id)],

            ],
            default                          => throw new \InvalidArgumentException("Operation '{$operation}' is not valid. Allowed: create, update")
        };
    }


    public function elements(): BelongsToMany
    {
        return $this->belongsToMany(MetaElement::class, 'meta_templates_elements', 'meta_template_id', 'meta_element_id')->withPivot(['name', 'title', 'settings', 'sort'])->orderByPivot('sort');
    }

}
