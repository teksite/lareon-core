<?php

namespace Lareon\Modules\Seo\App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Lareon\Modules\Seo\App\Enums\ChangeFrequencyEnum;
use Lareon\Modules\Seo\App\Enums\SeoStateEnum;

#[Fillable(['model', 'group', 'url', 'priority', 'change_frequency', 'last_modified', 'image', 'video', 'available_at', 'state'])]
class SeoSitemap extends Model
{
    protected function casts(): array
    {
        return [
            'priority'         => 'decimal,1',
            'change_frequency' => ChangeFrequencyEnum::class,
            'last_modified'    => 'timestamp',
            'image'            => 'json',
            'video'            => 'json',
            'available_at'     => 'timestamp',
            'state' => SeoStateEnum::class,

        ];
    }
}
