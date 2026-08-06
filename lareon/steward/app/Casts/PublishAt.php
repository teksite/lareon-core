<?php

namespace Lareon\Steward\App\Casts;

use Carbon\CarbonImmutable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Lareon\Steward\App\Enums\PublishStatusEnum;


class PublishAt implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): null|Carbon
    {
        return empty($value) ? null
            : Carbon::parse($value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        $status = $attributes['publish_status'] ?? null;

        if ($status === null) return $value;

        if ($status === PublishStatusEnum::DRAFTED->value) return null;

        if ($status === PublishStatusEnum::PUBLISHED->value && $value === null) return now();

        return $value;

    }
}
