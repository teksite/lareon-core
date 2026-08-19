<?php

namespace Lareon\Modules\Meta\App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Lareon\Modules\Meta\App\Models\MetaModel;

class SaveMetaDataService
{

    /**
     * @throws \Throwable
     */
    public function syncMetaData(Model $model, array $metaInputs = []): void
    {
        if (!$this->checkMethod($model)) return;

        DB::transaction(function () use ($metaInputs, $model) {

            $model->metaData()->delete();
            if (empty($metaInputs)) return [];

            $rows = [];

            foreach ($metaInputs as $key => $value) {
                if (!isset($value['element_id'])) {
                    Log::error('element_id field is not set in the element', ['instance' => $model, 'inputs' => $metaInputs]);
                    continue;
                }

                $content = removeNullValues($value['data'] ?? []);
                if (empty($content)) continue;

                $content = json_encode(removeNullValues($value['data'] ?? []));


                $rows[] = [
                    'key'         => $key,
                    'template_id' => $model->template_id,
                    'element_id'  => (int)$value['element_id'],
                    'model_type'  => $model::class,
                    'model_id'    => $model->id,
                    'content'     => $content,
                ];

            }
            if ($rows) MetaModel::insert($rows);

            return $rows;
        });
    }


    public function deleteMetaData(Model $model): void
    {
        if (!$this->checkMethod($model)) return;
        $model->metaData()->delete();
    }

    private function checkMethod(Model $model): bool
    {
        return method_exists($model, 'metaData');
    }

}
