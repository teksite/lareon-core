<?php

namespace Lareon\Modules\Seo\App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Lareon\Modules\Seo\App\Casts\ChangeFequencyCast;
use Lareon\Modules\Seo\App\Casts\SeoStateCast;

#[Fillable(['model', 'group', 'url', 'priority', 'change_frequency', 'last_modified', 'image', 'video', 'available_at', 'state'])]
class SeoSitemap extends Model
{
    protected function casts(): array
    {
        return [
            'priority'         => 'decimal,1',
            'change_frequency' => ChangeFequencyCast::class,
            'last_modified'    => 'timestamp',
            'image'            => 'json',
            'video'            => 'json',
            'available_at'     => 'timestamp',


            'state' => SeoStateCast::class,

        ];
    }
}
