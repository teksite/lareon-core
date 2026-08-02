<?php

namespace Lareon\Modules\Meta\App\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Lareon\Modules\Meta\App\Models\MetaModel;
use Lareon\Modules\Meta\App\Models\MetaTemplate;

trait HasTemplate
{
    public function template() :BelongsTo
    {
        return $this->belongsTo(MetaTemplate::class, 'template_id', 'id');
    }

    public function metaData()
    {
        return $this->morphMany(MetaModel::class, 'model');
    }
    public function getMetaData(string|null $key = null)
    {
        return $key === null
            ? $this->metaData
            : $this->metaData()->where('key' ,$key)->first();
    }


}
