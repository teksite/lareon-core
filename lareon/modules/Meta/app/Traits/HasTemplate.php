<?php

namespace Lareon\Modules\Meta\App\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Lareon\Modules\Meta\App\Models\MetaTemplate;

trait HasTemplate
{
    public function template() :BelongsTo
    {
        return $this->belongsTo(MetaTemplate::class, 'template_id', 'id');
    }

    public function metaData()
    {
        return $this->morphToMany(MetaTemplate::class, 'model', 'meta_models' ,'model_id' , 'model_type' ,'meta_template_id' );
    }


}
