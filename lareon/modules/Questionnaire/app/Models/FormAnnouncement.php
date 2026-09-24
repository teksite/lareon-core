<?php

namespace Lareon\Modules\Questionnaire\App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['form_id', 'emails', 'phones', 'telegram_ids', 'urls',])]
class FormAnnouncement extends Model
{
    protected $table = 'questionnaire_announcements';

    public static function rulesForModels(): array
    {
        return [
            'announcements.emails'       => 'nullable|string',
            'announcements.phones'       => 'nullable|string',
            'announcements.telegram_ids' => 'nullable|string',
            'announcements.urls'         => 'nullable|string',
        ];
    }

    public function form()
    {
        return $this->belongsTo(Form::class, 'form_id');
    }
}
