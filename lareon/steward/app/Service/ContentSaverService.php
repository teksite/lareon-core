<?php

namespace Lareon\Steward\App\Service;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Lareon\Modules\Seo\App\Traits\HasSeo;

class ContentSaverService
{

    public function create(Model $model, array $inputs = []): Model
    {
        $instance = $model::query()->create(Arr::except($inputs, ['seo', 'meta']));
        app(\SaveSitemapService::class)->syncSitemap($instance, $inputs['seo']['sitemap'] ?? []);
        return $instance;
    }


    public function update(Model $model, array $inputs = []): Model
    {
        $model::query()->update(Arr::except($inputs, ['seo', 'meta']));
        app(\SaveSitemapService::class)->syncSitemap($model, $inputs['seo']['sitemap'] ?? []);
        return $model->refresh();
    }


    public function delete(Model $model): Model
    {
        $model::query()->delete();
        app(\SaveSitemapService::class)->deleteSitemap($model);
        return $model->refresh();
    }


}
