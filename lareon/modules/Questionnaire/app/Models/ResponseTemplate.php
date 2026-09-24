<?php

namespace Lareon\Modules\Questionnaire\App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['form_id','template'])]
class ResponseTemplate extends Model
{
    protected $table = 'questionnaire_response_templates';

    public function form(): BelongsTo
    {
        return $this->belongsTo(form::class);
    }
}
