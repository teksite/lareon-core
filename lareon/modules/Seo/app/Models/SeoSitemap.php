<?php

namespace Lareon\Modules\Seo\App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lareon\Modules\Seo\App\Enums\ChangeFrequencyEnum;
use Lareon\Modules\Seo\App\Enums\SeoStateEnum;

#[Fillable(['model', 'group', 'url', 'priority', 'change_frequency', 'last_modified', 'image', 'video', 'available_at'])]
class SeoSitemap extends Model
{
    protected function casts(): array
    {
        return [
            'priority'=>'decimal:1',
            'last_modified'=>'datetime',
            'available_at'=>'datetime',
            'image'=>'array',
            'video'=>'array',
        ];
    }

    public function model()
    {
        return $this->morphTo('model');
    }

}
