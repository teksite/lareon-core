<?php

namespace Lareon\Modules\Seo\App\Services;

use Illuminate\Database\Eloquent\Model;
use Lareon\Modules\Seo\App\Models\SeoMetaModel;

class SaveMetaTagService
{
    public function sync(Model $model, array $inputs = []): void
    {
        if (!$this->checkMethod($model)) return;

        SeoMetaModel::query()->updateOrCreate(
            [
                'model_type' => get_class($model),
                'model_id'   => $model->getKey(),
            ],
            [
                'title'         => $inputs['title'] ?? null,
                'description'   => $inputs['description'] ?? null,
                'keywords'      => explode('|', $inputs['keywords'] ?? null),
                'canonical_url' => $inputs['canonical_url'] ?? null,
                'indexable'     => $inputs['indexable'] ?? 0,
                'followable'    => $inputs['followable'] ?? 0,
                'open_graph'    => removeNullValues($inputs['open_graph'] ?? null),
            ]
        );
    }

    public function delete(Model $model): void
    {
        SeoMetaModel::query()->where('model_type', $model::class)->where('model_id', $model->getKey())->delete();
    }


    private function checkMethod(Model $model): bool
    {
        return method_exists($model, 'metaTag');
    }
}
