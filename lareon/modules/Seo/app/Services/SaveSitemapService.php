<?php
namespace Lareon\Modules\Seo\App\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Lareon\Modules\Seo\App\Models\SeoSitemap;
use Lareon\Steward\App\Enums\PublishStatusEnum;

class SaveSitemapService
{

    public function syncSitemap(Model $model, array $inputs = []): void
    {
        if (!$this->checkMethod($model)) return;

        if (!method_exists($model, 'path')) return;;

        $url = $model->path();

        if (!$url) {
            SeoSitemap::query()->delete();
            
            return;
        };

        SeoSitemap::query()->updateOrCreate(
            [
                'model_type' => get_class($model),
                'model_id' => $model->getKey(),
            ],
            [
                'group'            => $this->group($model),
                'url'              => $url,
                'last_modified'    => $this->lastModified($model),
                'available_at'     => $this->availableAt($model),
                'priority'         => $inputs['priority'] ?? config('seo.sitemap.default_priority', 0.5),
                'change_frequency' => $inputs['change_frequency'] ?? config('seo.sitemap.default_change_frequency', 'yearly'),
                'image'            => $inputs['images'] ?? null,
                'video'            => $inputs['videos'] ?? null,
            ]
        );
    }

    public function deleteSitemap(Model $model): void
    {
        if (!$this->checkMethod($model)) return;
        SeoSitemap::query()->where('model_type', $model::class)->where('model_id', $model->getKey())->delete();
    }


    private function checkMethod(Model $model): bool
    {
        return method_exists($model, 'sitemap');
    }

    private function group(Model $model): string
    {
        if (method_exists($model, 'siteMapGroup')) return $model->siteMapGroup();

        if ($attribute = $model->siteMapGroup) return $attribute;


        return Str::snake(class_basename($model));
    }

    private function lastModified(Model $model): Carbon
    {
        $createdAt = $model->created_at ? Carbon::parse($model->created_at) : Carbon::now();

        $updatedAt = $model->updated_at ? Carbon::parse($model->updated_at) : $createdAt;

        if ($model->hasCast('publish_status') && $model->publish_status === PublishStatusEnum::DRAFTED) return $updatedAt;

        if (!$model->hasCast('published_at')) return $updatedAt;

        if (!$model->published_at) return $updatedAt;

        $publishedAt = Carbon::parse($model->published_at);

        return $publishedAt->greaterThan($updatedAt) ? $publishedAt : $updatedAt;
    }

    private function availableAt(Model $model): ?Carbon
    {
        if ($model->hasCast('publish_status') && $model->publish_status === PublishStatusEnum::DRAFTED) return null;

        if (!$model->hasCast('published_at')) return $model->created_at ? Carbon::parse($model->created_at) : Carbon::now();

        return $model->published_at ? Carbon::parse($model->published_at) : null;
    }
}
