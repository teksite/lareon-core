<?php

namespace Lareon\Modules\Meta\App\Traits;

use Illuminate\Support\Facades\Log;
use Lareon\Modules\Meta\App\Models\MetaModel;
use Lareon\Modules\Page\App\Models\Page;

trait HasMetaLogic
{
    public function attachMeta(MetaModel $metaModel)
    {
        $page->metaData()->delete();
        foreach ($inputs['meta_data'] as $key => $value) {
            if (isset($value['id'])) {
                Log::error('element_id field is not set in the element', ['instance' => $page, 'inputs' => $inputs]);
                continue;
            }
            MetaModel::query()->create([
                    'key'         => $key,
                    'template_id' => $page->template_id,
                    'element_id'  => (int)$value['element_id'],
                    'model_type'  => Page::class,
                    'model_id'    => $page->id,
                    'content'     => $value['data'],
                ]
            );
        }
    }
}
