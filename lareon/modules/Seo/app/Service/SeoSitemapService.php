<?php

namespace Lareon\Modules\Seo\App\Service;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Lareon\Modules\Seo\App\Models\SeoSitemap;
use Lareon\Steward\App\Enums\PublishStatusEnum;

class SeoSitemapService
{

    public function syncSitemap(Model $model, array $inputs = []): void
    {
        $model->refresh();
        if (!method_exists($model, 'path')) return;
        $url = $model->path();
        if (!$url) return;

        SeoSitemap::query()->updateOrCreate(
            [
                'model_type' => $model::class,
                'model_id'   => $model->getKey(),
            ],
            [
                'group'         => $this->group($model),
                'url'           => $url,
                'last_modified' => $this->lastModified($model),
                'available_at'  => $this->availableAt($model),
                'priority'         => $inputs['priority'] ?? config('seo.sitemap.default_priority', 0.5),
                'change_frequency' => $inputs['change_frequency'] ?? config('seo.sitemap.default_change_frequency', 'yearly'),
                'image'            => $inputs['images'] ?? null,
                'video'            => $inputs['videos'] ?? null,

            ]
        );
    }

    public function deleteSitemap(Model $model): void
    {
        SeoSitemap::query()->where('model_type', $model::class)->where('model_id', $model->getKey())->delete();
    }


    protected function group(Model $model): string
    {
        return $model->siteMapGroup ?? Str::snake(class_basename($model));
    }
    protected function lastModified(Model $model): Carbon
    {
        $createdAt = $model->created_at ? Carbon::parse($model->created_at) : Carbon::now();

        $updatedAt = $model->updated_at ? Carbon::parse($model->updated_at) : $createdAt;

        if ($model->hasCast('publish_status') && $model->publish_status === PublishStatusEnum::DRAFTED) return $updatedAt;

        if (!$model->hasCast('published_at')) return $updatedAt;

        if (!$model->published_at) return $updatedAt;

        $publishedAt = Carbon::parse($model->published_at);

        return $publishedAt->greaterThan($updatedAt) ? $publishedAt : $updatedAt;
    }
    protected function availableAt(Model $model): ?Carbon
    {
        if ($model->hasCast('publish_status') && $model->publish_status === PublishStatusEnum::DRAFTED) return null;

        if (!$model->hasCast('published_at')) return $model->created_at ? Carbon::parse($model->created_at) : Carbon::now();

        return $model->published_at ? Carbon::parse($model->published_at) : null;
    }

}
