<?php

namespace Lareon\Modules\Fence\App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Lareon\Modules\Fence\App\Enums\GuardType;

#[Fillable(['ip_address', 'type'])]
class Fence extends Model
{
    protected function casts(): array
    {
        return [
            'type' => GuardType::class,
        ];
    }


    public static function rules(): array
    {
        return [
            'ip_address' => 'required|ip',
            'type'       => ['required', Rule::enum(GuardType::class)],
        ];
    }
}
