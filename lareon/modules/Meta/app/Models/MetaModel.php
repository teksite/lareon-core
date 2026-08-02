<?php

namespace Lareon\Modules\Meta\App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphPivot;

#[Fillable(['template_id', 'model_type', 'model_id', 'content'])]
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
            'template_id'   => 'required|integer',
            'model_id'   => 'required|integer',
            'model_type' => 'required|string',
            'content'    => 'nullable|array',
        ];

    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(MetaElementTemplate::class, 'template_id');
    }


    public function model(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo('model');
    }
}
