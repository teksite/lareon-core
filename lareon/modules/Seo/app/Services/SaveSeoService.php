<?php

namespace Lareon\Modules\Seo\App\Services;

use Illuminate\Database\Eloquent\Model;

class SaveSeoService
{
    public function syncSeo(Model $model, array $inputs = []): void
    {
        (new SaveMetaTagService())->sync($model, $inputs['meta'] ?? []);
        (new SaveSitemapService())->sync($model, $inputs['sitemap'] ?? []);
        (new SaveSchemaService())->sync($model, $inputs['sitemap'] ?? []);
    }


    public function deleteSeo(Model $model): void
    {
        (new SaveMetaTagService())->delete($model);
        (new SaveMetaTagService())->delete($model);
        (new SaveSchemaService())->delete($model);
    }


}
