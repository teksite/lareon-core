<?php

namespace Lareon\Steward\App\Service;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Lareon\Modules\Meta\App\Services\SaveMetaDataService;
use Lareon\Modules\Seo\App\Services\SaveSitemapService;

class ContentSaverService
{

    public static function create(Model $model, array $inputs = []): Model
    {
        $instance = $model::query()->create(Arr::except($inputs, ['seo', 'meta_data']));
        app(SaveSitemapService::class)->syncSitemap($instance, $inputs['seo']['sitemap'] ?? []);
        return $instance;
    }


    /**
     * @throws \Throwable
     */
    public static function update(Model $model, array $inputs = []): Model
    {
        $model::query()->update(Arr::except($inputs, ['seo', 'meta_data']));
        $model = $model->refresh();

        app(SaveSitemapService::class)->syncSitemap($model, $inputs['seo']['sitemap'] ?? []);
        app(SaveMetaDataService::class)->syncMetaData($model, $inputs['meta_data'] ?? []);

        return $model;
    }


    public static function delete(Model $model): bool
    {
        $model::query()->delete();
        app(\SaveSitemapService::class)->deleteSitemap($model);
        app(SaveMetaDataService::class)->deleteMetaData($model);

        return true;
    }
}
