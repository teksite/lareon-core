<?php
namespace Lareon\Modules\Seo\App\Services;

use Illuminate\Database\Eloquent\Model;

class SaveSeoService
{
    public function syncSeo(Model $model, array $inputs = []): void
    {
        (new SaveMetaTagService())->syncMetaTag($model, $inputs['meta'] ,[]);
        (new SaveSitemapService())->syncSitemap($model, $inputs['sitemap'] ,[]);
    }


    public function deleteSeo(Model $model): void
    {
        (new SaveMetaTagService())->deleteMetaTag($model);
        (new SaveSitemapService())->deleteSitemap($model);
    }



}
