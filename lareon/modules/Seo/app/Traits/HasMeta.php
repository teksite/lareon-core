<?php

namespace Lareon\Modules\Seo\App\Traits;

use Lareon\Modules\Seo\App\Models\SeoMetaModel;

trait HasMeta
{
    public function metaTag()
    {
        return $this->morphOne(SeoMetaModel::class, 'model');
    }
}
