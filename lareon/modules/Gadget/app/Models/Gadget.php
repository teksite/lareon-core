<?php

namespace Lareon\Modules\Gadget\App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

#[Fillable(['label', 'title', 'body', 'template'])]
class Gadget extends Model
{
    use SoftDeletes;

    public function scopeGetGadget(Builder $query, string $label,)
    {
        return $query->firstWhere('label', $label)?->body;
    }

    public static function rules(): array
    {
        return [
            'title'    => 'required|string',
            'body'     => 'nullable|string',
            'template' => 'nullable|string',
        ];
    }

    public static function boot(): void
    {
        parent::boot();
        static::creating(function ($model,) {
            $model->label = 'gadget_'.time();
        });
    }

}
