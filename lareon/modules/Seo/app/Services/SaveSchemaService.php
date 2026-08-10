<?php

namespace Lareon\Modules\Seo\App\Services;

use Illuminate\Database\Eloquent\Model;
use Lareon\Modules\Seo\App\Models\SeoSchemaModel;

class SaveSchemaService
{
    public function sync(Model $model, array $inputs = []): void
    {

        if (!$this->checkMethod($model)) return;

        SeoSchemaModel::query()->updateOrCreate(
            [
                'model_type' => get_class($model),
                'model_id'   => $model->getKey(),
            ],
            [
                'schema'         => removeNullValues($inputs ?? null),
            ]
        );
    }

    public function delete(Model $model): void
    {
        SeoSchemaModel::query()->where('model_type', $model::class)->where('model_id', $model->getKey())->delete();
    }


    private function checkMethod(Model $model): bool
    {
        return method_exists($model, 'schemaStructure');
    }
}
