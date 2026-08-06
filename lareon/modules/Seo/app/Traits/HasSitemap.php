<?php

namespace Lareon\Modules\Seo\App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Lareon\Modules\Seo\App\Models\SeoSitemap;
use Lareon\Steward\App\Enums\PublishStatusEnum;

trait HasSitemap
{
    public function siteMap(): MorphTo
    {
        return $this->morphTo(SeoSitemap::class, 'model');
    }


    public function syncSiteMap($inputs, Model|null $instance = null): MorphTo
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
            'change_frequency' => $inputs['priority'] ?? config('seo.sitemap.default_change_frequency', 'yearly'),
            'last_modified'    => $this->evaluateLastModifiedDate($instance),
            'available_at'     => $this->evaluateAvailableDate($instance),

            'image' => $data['images'] ?? null,
            'video' => $data['images'] ?? null,


        ]);
    }

    private function getGroupName(Model $model): string
    {
        $group = $model->siteMapGroup;
        if ($group) return $group;

        $path = explode('\\', $model);
        return array_pop($path);
    }

    public function evaluateLastModifiedDate(Model $model): Carbon|\Carbon\CarbonInterface
    {
        $publishAt = $model->published_at ? Carbon::parse($model->published_at) : null;
        $createdAt = $model->created_at ? Carbon::parse($model->created_at) : now();
        $updatedAt = $model->updated_at ? Carbon::parse($model->updated_at) : ($createdAt);

        if ($publishAt) {
            if ($publishAt->gte($updatedAt)) return $publishAt;
            if ($updatedAt->gte($publishAt)) return $updatedAt;
        }
        return $updatedAt;
    }

    public function evaluateAvailableDate(Model $model): Carbon|\Carbon\CarbonInterface
    {
        $publishAt = $model->published_at ? Carbon::parse($model->published_at) : null;
        $createdAt = $model->created_at ? Carbon::parse($model->created_at) : now();

        if ($publishAt) return $publishAt;

        return $createdAt;
    }


}
