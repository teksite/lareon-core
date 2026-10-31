<?php

namespace Lareon\Modules\Seo\App\Traits;

use Illuminate\Database\Eloquent\Model;
use Lareon\Modules\Seo\App\Models\SeoMetaModel;
use Lareon\Modules\Seo\App\Models\SeoSchemaModel;
use Lareon\Modules\Seo\App\Models\SeoSitemap;

trait HasSeo
{
    use HasMeta, HasSchema, HasSitemap;


    public function seo(string|array $only = ['*']): array
    {
        $data = [
            'meta'    => $this->metaTag,
            'schema'  => $this->schemaStructure,
            'sitemap' => $this->sitemap,
        ];

        $only = (array) $only;

        return in_array('*', $only, true)
            ? $data
            : array_intersect_key($data, array_flip($only));
    }


    public static function seoRules(): array
    {
        return [
            ...SeoMetaModel::rules(),
            ...SeoSitemap::rules(),
            ...SeoSchemaModel::rules(),
        ];
    }
}
