<?php

namespace Lareon\Modules\Seo\App\Traits;

use Illuminate\Database\Eloquent\Model;
use Lareon\Modules\Seo\App\Models\SeoMetaModel;
use Lareon\Modules\Seo\App\Models\SeoSchemaModel;
use Lareon\Modules\Seo\App\Models\SeoSitemap;

trait HasSeo
{
    use HasMeta, HasSchema, HasSitemap;


    public function seo(): array
    {
        return [
            'meta'    => $this->metaTag,
            'schema'  => $this->schemaStructure,
            'sitemap' => $this->sitemap,
        ];
    }

    public static function seoRules(): array
    {
        return [
            SeoMetaModel::rules(),
            SeoSitemap::rules(),
            SeoSchemaModel::rules(),
        ];
    }
}
