<?php

namespace Lareon\Modules\Seo\App\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasSeo
{
    use HasMeta, HasSchema, HasSitemap;


    public function seo(): array
    {
        return [
            'meta'=>$this->metaTag,
            'schema'=>$this->schemaStructure,
            'sitemap'=>$this->sitemap
        ];
    }
}
