<?php

namespace Lareon\Modules\Meta\App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Lareon\Modules\Meta\App\Models\MetaModel;
use Lareon\Modules\Meta\App\Models\MetaTemplate;

trait HasTemplate
{
    public function template(): BelongsTo
    {
        return $this->belongsTo(MetaTemplate::class, 'template_id', 'id');
    }

    public function metaData()
    {
        return $this->morphMany(MetaModel::class, 'model');
    }

    public function getMetaData(string|null $key = null)
    {
        return $key === null
            ? $this->metaData
            : $this->metaData()->where('key', $key)->first();
    }

    /**
     * @throws \Throwable
     */
    public function saveMetaData(array $metaInputs = [], null|Model $model = null): null|array
    {
        $model ??= $this;
        if (!method_exists($model, 'metaData')) return null;

        return DB::transaction(function () use ($metaInputs, $model) {

            $model->metaData()->delete();
            if (empty($metaInputs)) return [];

            $rows = [];

            foreach ($metaInputs as $key => $value) {
                if (!isset($value['element_id'])) {
                    Log::error('element_id field is not set in the element', ['instance' => $model, 'inputs' => $metaInputs]);
                    continue;
                }

                $rows[] = [
                    'key'         => $key,
                    'template_id' => $model->template_id,
                    'element_id'  => (int)$value['element_id'],
                    'model_type'  => $model::class,
                    'model_id'    => $model->id,
                    'content'     => $value['data'] ?? null,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];

            }
            if ($rows) MetaModel::insert($rows);

            return $rows;
        });
    }


    public function deleteMetaData(array $metaInputs = [], null|Model $model = null): null|array
    {
        $model ??= $this;
        if (!method_exists($model, 'metaData')) return null;

        return DB::transaction(function () use ($metaInputs, $model) {

            $model->metaData()->delete();
            if (empty($metaInputs)) return [];

            $rows = [];

            foreach ($metaInputs as $key => $value) {
                if (!isset($value['element_id'])) {
                    Log::error('element_id field is not set in the element', ['instance' => $model, 'inputs' => $metaInputs]);
                    continue;
                }

                $rows[] = [
                    'key'         => $key,
                    'template_id' => $model->template_id,
                    'element_id'  => (int)$value['element_id'],
                    'model_type'  => $model::class,
                    'model_id'    => $model->id,
                    'content'     => $value['data'] ?? null,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];

            }
            if ($rows) MetaModel::insert($rows);

            return $rows;
        });
    }
}
