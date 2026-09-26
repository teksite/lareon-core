<?php

namespace Lareon\Modules\Questionnaire\App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['form_id', 'rules'])]
class FormRule extends Model
{
    protected $table = 'questionnaire_rules';

    protected function casts(): array
    {
        return [
            'rules' => 'json',
        ];
    }

    public static function rulesForModels(): array
    {
        return [
            'rules'     => 'sometimes|array',
            'rules.*.*' => 'string|required',
        ];
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class, 'form_id');
    }
}
