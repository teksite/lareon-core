<?php

namespace Lareon\Modules\Seo\App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Lareon\Modules\Seo\App\Enums\ChangeFrequencyEnum;

#[Fillable(['model_type', 'model_id', 'group', 'url', 'priority', 'change_frequency', 'last_modified', 'image', 'video', 'available_at'])]
class SeoSitemap extends Model
{


    protected function casts(): array
    {
        return [
            'priority'         => 'decimal:1',
            'last_modified'    => 'datetime',
            'available_at'     => 'datetime',
            'change_frequency' => ChangeFrequencyEnum::class,
            'image'            => 'array',
            'video'            => 'array',
        ];
    }


    public static function rules(): array
    {
        return [
            'seo.sitemap' => 'sometimes|array',

            'seo.sitemap.priority'         => 'nullable|decimal:0.1|min:0.1|max:0.9',
            'seo.sitemap.change_frequency' => ['required', 'string', Rule::enum(ChangeFrequencyEnum::class)],
            'images'                        =>'nullable|sometimes|array',
            'videos'                        =>'nullable|sometimes|array',
        ];
    }

    public function model(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo('model');
    }

}
