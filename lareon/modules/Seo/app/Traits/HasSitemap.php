<?php

namespace Lareon\Modules\Seo\App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;
use Lareon\Modules\Seo\App\Models\SeoSitemap;
use Lareon\Steward\App\Enums\PublishStatusEnum;

trait HasSitemap
{
    public function siteMap(): MorphTo
    {
        return $this->morphTo(SeoSitemap::class, 'model');
    }


    public function syncSiteMap($inputs, Model|null $instance = null): void
    {
        $instance ??= $this;

        $instance = $instance->refresh();

        if (!method_exists($instance, 'path') || $instance->path() === null) return;

        SeoSitemap::query()->updateOrCreate(
            [
                'model_id'   => $this->id,
                'model_type' => get_class($this),
            ], [
            'group'            => $this->getGroupName($instance),
            'url'              => $this->path(),
            'priority'         => $inputs['priority'] ?? config('seo.sitemap.default_priority', 0.5),
            'change_frequency' => $inputs['change_frequency'] ?? config('seo.sitemap.default_change_frequency', 'yearly'),
            'last_modified'    => $this->evaluateLastModifiedDate($instance),
            'available_at'     => $this->evaluateAvailableDate($instance),

            'image' => $data['images'] ?? null,
            'video' => $data['videos'] ?? null,


        ]);
    }

    private function getGroupName(Model $model): string
    {
        return $model->siteMapGroup ?? Str::snake(class_basename($model));
    }

    public function evaluateLastModifiedDate(Model $model): CarbonInterface
    {
        $createdAt = $model->created_at ? Carbon::parse($model->created_at) : now();

        $updatedAt = $model->updated_at ? Carbon::parse($model->updated_at) : $createdAt;

        if ($model->hasCast('publish_status') && $model->publish_status === PublishStatusEnum::DRAFTED) return $updatedAt;

        if (!$model->hasCast('published_at')) return $updatedAt;

        if (!$model->published_at) return $updatedAt;

        $publishedAt = Carbon::parse($model->published_at);

        return $publishedAt->greaterThan($updatedAt) ? $publishedAt : $updatedAt;
    }

    public function evaluateAvailableDate(Model $model): null|Carbon
    {
        if ($model->hasCast('publish_status') && $model->publish_status === PublishStatusEnum::DRAFTED) return null;

        if (!$model->hasCast('published_at')) return $model->created_at ? Carbon::parse($model->created_at) : Carbon::now();

        return $model->published_at ? Carbon::parse($model->published_at) : null;
    }

}
