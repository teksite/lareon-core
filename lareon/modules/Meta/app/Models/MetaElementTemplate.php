<?php

namespace Lareon\Modules\Meta\App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Validation\Rule;


#[Fillable(['model_type', 'template_id', 'element_id', 'title', 'name', 'settings', 'sort'])]
#[Table('meta_templates_elements')]
class MetaElementTemplate extends Pivot
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
            'template_id' => 'required|integer|exists:meta_templates,id',
            'element_id' => 'required|integer|exists:meta_elements,id',
            'title'      => 'required|string|max:100',
            'name'       => 'required|string',
            'settings'   => 'required|array',
            'sort'       => 'required|numeric',
        ];
    }

    public function element(): BelongsTo
    {
        return $this->belongsTo(MetaElement::class, 'element_id');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(MetaElement::class, 'template_id');
    }
}
