<?php

namespace Lareon\Modules\Seo\App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable('model_id', 'model_type', 'schema',)]
class SeoSchemaModel extends Model
{
    protected function casts(): array
    {
        return [
            'schema'=>'json',
        ];
    }
    public static function rules(): array
    {
        return [
            'seo.schema' => 'sometimes|array',
        ];
    }
}
