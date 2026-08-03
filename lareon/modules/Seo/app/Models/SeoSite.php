<?php

namespace Lareon\Modules\Seo\App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Lareon\Modules\Seo\App\Enums\SeoStateEnum;

#[Fillable(['key','value','state'])]
class SeoSite extends Model
{
    protected function casts(): array
    {
        return [
            'key' => 'string',
            'value' => 'json',
            'state' => SeoStateEnum::class
        ];
    }

    public static function rules(): array
    {
        return [
            'key'=>'required|string',
            'value'=>'required|array',
            'state'=>'required|boolean',
        ];
    }
}
