<?php

namespace Lareon\Modules\Questionnaire\App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lareon\Modules\Questionnaire\App\Events\NewInboxEvent;
use Lareon\Steward\App\Models\Admin;

#[Fillable(['form_id', 'title', 'data', 'url', 'note', 'reader_id', 'ip_address', 'read_at'])]
class FormInbox extends Model
{
    use SoftDeletes;

    protected $table = 'questionnaire_inboxes';

    protected static function boot(): void
    {
        parent::boot();
//        static::created(function (FormInbox $inbox,): void {
//            event(new NewInboxEvent($inbox));
//        });
    }

    protected function casts(): array
    {
        return [
            'data'    => 'json',
            'read_at' => 'datetime',
            'note'    => 'json',
        ];
    }

    public static function rules(): array
    {
        return [
            "form_title" => 'required|string|max:150|exists:questionnaire_forms,title',
            "data"       => 'array',
            "url"        => 'nullable|string',
            "note"       => 'nullable|string',
            "read_at"    => 'nullable|datetime',
            "user_id"    => 'nullable|integer',
        ];
    }

    public static function rulesForModels(): array
    {
        return [
            'data_info'            => 'array|required',
            'data_info.identify'   => 'required|string',
            'data_info.url'        => 'required|string',
            'data_info.page_title' => 'nullable|string',
            'data_info.fullname'   => 'prohibited',
        ];
    }

    public function readBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'reader_id');
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class, 'form_id');
    }

    public function markAsRead(Admin|null $admin = null,): void
    {
        if ($this->read_at === null || $this->reader_id === null) {
            $adminId = $admin === null ? auth('admin')->id() : $admin->id;
            $this->forceFill(['read_at' => now(), 'reader_id' => $adminId])->save();
        }
    }
}
