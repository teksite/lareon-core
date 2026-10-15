<?php

namespace Lareon\Modules\Ticketing\App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Lareon\Modules\Ticketing\App\Enums\SenderType;

#[Fillable(['ticket_id', 'message_id', 'uploaded_by_type', 'uploaded_by_id', 'disk', 'path', 'original_name', 'mime_type', 'size_bytes',])]
class TicketAttachment extends Model
{
    protected $table = 'ticketing_attachments';

    protected function casts(): array
    {
        return [
            'uploaded_by_type' => SenderType::class,
        ];
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function message(): BelongsTo
    {
        return $this->belongsTo(TicketMessage::class, 'message_id');
    }


    public function url(): string
    {
        return Storage::disk($this->disk)->url($this->path);

    }

    public function humanSize(): string
    {
        $bytes = $this->size_bytes;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;

        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 1).' '.$units[$i];
    }
}
