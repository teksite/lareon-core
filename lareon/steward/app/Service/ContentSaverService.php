<?php

namespace Lareon\Steward\App\Service;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Lareon\Modules\Meta\App\Services\SaveMetaDataService;
use Lareon\Modules\Seo\App\Services\SaveSeoService;

class ContentSaverService
{

    public static function create(Model $model, array $inputs = []): Model
    {
        $instance = $model::query()->create(Arr::except($inputs, ['seo', 'meta_data']));
        app(SaveSeoService::class)->syncSeo($instance, $inputs['seo'] ?? []);
        return $instance;
    }


    /**
     * @throws \Throwable
     */
    public static function update(Model $model, array $inputs = []): Model
    {
        $model::query()->update(Arr::except($inputs, ['seo', 'meta_data']));
        $model = $model->refresh();

        app(SaveSeoService::class)->syncSeo($model, $inputs['seo'] ?? []);

        return $model;
    }


    public static function delete(Model $model): bool
    {
        $model::query()->delete();
        app(SaveSeoService::class)->deleteSeo($model);
        app(SaveMetaDataService::class)->deleteMetaData($model);

        return true;
    }
}
