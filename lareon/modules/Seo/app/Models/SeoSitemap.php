<?php

namespace Lareon\Modules\Seo\App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Lareon\Modules\Seo\App\Enums\ChangeFrequencyEnum;

#[Fillable(['model_type','model_id', 'group', 'url', 'priority', 'change_frequency', 'last_modified', 'image', 'video', 'available_at'])]
class SeoSitemap extends Model
{


    protected function casts(): array
    {
        return [
            'priority'=>'decimal:1',
            'last_modified'=>'datetime',
            'available_at'=>'datetime',
            'change_frequency'=>ChangeFrequencyEnum::class,
            'image'=>'array',
            'video'=>'array',
        ];
    }


    public static function rules(): array
    {
        return [];
    }

    public function model(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo('model');
    }

}
