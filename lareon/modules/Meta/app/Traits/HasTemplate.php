<?php

namespace Lareon\Modules\Meta\App\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Lareon\Modules\Meta\App\Models\MetaModel;
use Lareon\Modules\Meta\App\Models\MetaTemplate;

trait HasTemplate
{
    public function template(): BelongsTo
    {
        return $this->belongsTo(MetaTemplate::class, 'template_id', 'id');
    }

    public function metaData(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(MetaModel::class, 'model');
    }


    public function getMeta($key, string|array $columns = '*') : mixed
    {
        $columnsArray = (array)$columns;

        $record = $this->relationLoaded('metaData')
            ? $this->metaData->firstWhere('key', $key)
            : $this->metaData()->where('key', $key)->first();

        if (!$record) return null;

        if (in_array('*', $columnsArray, true)) return $record;

        if (is_string($columns)) return $record->{$columns};
        return $record->only($columnsArray);
    }
}
