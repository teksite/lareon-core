<?php

namespace Lareon\Steward\App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Lareon\Steward\App\Enums\PublishStatusEnum;


class PublishAt implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        return $value;
    }

    public function set($model, string $key, $value, array $attributes)
    {

        if ($attributes['publish_status'] === PublishStatusEnum::DRAFTED->value) return null;

        if ($attributes['publish_status'] === PublishStatusEnum::PUBLISHED->value && $value === null) return now();

        return $value;

    }
}
