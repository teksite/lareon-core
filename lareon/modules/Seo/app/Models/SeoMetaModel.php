<?php

namespace Lareon\Modules\Seo\App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable('model_id', 'model_type', 'title', 'description', 'keywords', 'canonical_url', 'indexable', 'followable', 'open_graph')]
class SeoMetaModel extends Model
{
    protected function casts(): array
    {
        return [
            'keywords'   => 'array',
            'indexable'  => 'boolean',
            'followable' => 'boolean',
            'open_graph' => 'json',
        ];
    }


    public static function rules(): array
    {
        return [
            'seo.meta'=>'sometimes|array',

            'seo.meta.title'=>'nullable|string|max:255',
            'seo.meta.description'=>'nullable|string|max:255',
            'seo.meta.keywords'=>'nullable|string|max:255',
            'seo.meta.canonical_url'=>'nullable|string|max:255',
            'seo.meta.indexable'=>'sometimes|nullable|in:0,1',
            'seo.meta.followable'=>'sometimes|nullable|in:0,1',
            'seo.meta.open_graph'=>'nullable|array',
        ];
    }
}
