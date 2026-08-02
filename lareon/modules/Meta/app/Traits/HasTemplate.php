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

}
