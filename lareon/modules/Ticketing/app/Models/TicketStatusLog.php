<?php

namespace Lareon\Modules\Ticketing\App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Lareon\Modules\Ticketing\App\Enums\ChangedByType;
use Lareon\Modules\Ticketing\App\Enums\TicketStatusType;

#[Fillable(['ticket_id', 'from_status', 'to_status', 'changed_by_type', 'changed_by_id', 'note', 'created_at',])]
class TicketStatusLog extends Model
{
    protected $table = 'ticketing_status_logs';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'from_status'     => TicketStatusType::class,
            'to_status'       => TicketStatusType::class,
            'changed_by_type' => ChangedByType::class,
            'created_at'      => 'datetime',
        ];
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }
}
