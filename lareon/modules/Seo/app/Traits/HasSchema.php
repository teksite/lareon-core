<?php

namespace Lareon\Modules\Seo\App\Traits;

use Lareon\Modules\Seo\App\Models\SeoSchemaModel;

trait HasSchema
{
    public function schemaStructure()
    {
        return $this->morphOne(SeoSchemaModel::class, 'model');
    }
}
