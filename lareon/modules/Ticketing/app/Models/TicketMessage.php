<?php

namespace Lareon\Modules\Ticketing\App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Lareon\Modules\Ticketing\App\Enums\SenderType;
use Lareon\Modules\Ticketing\Database\Factory\TicketMessageFactory;
use Lareon\Modules\User\App\Models\User;
use Lareon\Steward\App\Models\Admin;

#[UseFactory(TicketMessageFactory::class)]
#[Fillable('ticket_id', 'sender_type', 'sender_id', 'body', 'is_internal_note', 'meta',)]
class TicketMessage extends Model
{
    /** @use HasFactory<TicketMessageFactory> */
    use HasFactory;

    protected $table = 'ticketing_messages';


    protected function casts(): array
    {
        return [
            'sender_type'      => SenderType::class,
            'is_internal_note' => 'boolean',
            'meta'             => 'json',
        ];
    }


    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }


    public function senderDisplayName(): string
    {
        return $this->sender_type->label();
    }

    public function admin(): mixed
    {
        if ($this->sender_id === null) return null;
        return Admin::find($this->sender_id);
    }

    public function user(): mixed
    {
        if ($this->sender_id === null) return null;
        return User::find($this->sender_id);
    }

    public function authorName(): mixed
    {
        if ($this->sender_id === null) return null;

        return match ($this->sender_type) {
            SenderType::Admin  => $this->admin()->name,
            SenderType::User   => $this->user()->name,
            SenderType::Ai     => 'ai',
            SenderType::System => 'system',
        };
    }


    public function attachments(): HasMany
    {
        return $this->hasMany(TicketAttachment::class, 'message_id');
    }

}
