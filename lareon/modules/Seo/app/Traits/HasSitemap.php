<?php

namespace Lareon\Modules\Seo\App\Traits;

use Lareon\Modules\Seo\App\Models\SeoSitemap;

trait HasSitemap
{
    public function sitemap()
    {
        return $this->morphOne(SeoSitemap::class, 'model');
    }
}
