<?php

namespace Lareon\Modules\Meta\App\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Lareon\Modules\Meta\App\Models\MetaTemplate;

trait HasTemplate
{
    public function template() :HasOne
    {
        return $this->hasOne(MetaTemplate::class, 'template_id', 'id');
    }
}
