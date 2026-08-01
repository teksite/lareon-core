<?php

namespace Lareon\Modules\Meta\App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\Rule;


#[Fillable(['model_type', 'meta_template_id', 'element_id', 'title', 'name', 'settings', 'sort'])]
#[Table('meta_templates_elements')]
class MetaFieldTemplate extends Model
{
    protected function casts(): array
    {
        return [
            'settings' => 'json',
            'sort'     => 'integer',
        ];
    }

    public static function rules(string $operation, int|null $id = null): array
    {
        return [
            'model_type' => 'required|string',
            'meta_template_id' => 'required|integer|exists:meta_templates,id',
            'meta_element_id' => 'required|integer|exists:meta_elements,id',
            'title'      => 'required|string|max:100',
            'name'       => 'required|string',
            'settings'   => 'required|array',
            'sort'       => 'required|numeric',
        ];
    }

    public function element(): BelongsTo
    {
        return $this->belongsTo(MetaElement::class, 'meta_element_id');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(MetaElement::class, 'meta_template_id');
    }
}
