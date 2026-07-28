<?php

namespace Lareon\Modules\Meta\App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['field_id', 'model_type', 'model_id', 'content'])]
#[Table('meta_models')]
class MetaModel extends Model
{
    protected function casts(): array
    {
        return [
            'content' => 'json',
        ];
    }

    public static function rules(string $operation, int|null $id = null): array
    {
        return [
            'field_id'   => 'required|integer',
            'model_id'   => 'required|integer',
            'model_type' => 'required|string',
            'content'    => 'nullable|array',
        ];

    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(MetaFieldTemplate::class, 'field_id');
    }

    public function element()
    {
        return $this->hasOneThrough(MetaElement::class, MetaFieldTemplate::class, 'id', 'id', 'field_id', 'meta_element_id');
    }


    public function model(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo('model');
    }
}
