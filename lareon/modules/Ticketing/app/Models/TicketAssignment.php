<?php

namespace Lareon\Modules\Ticketing\App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Lareon\Modules\Ticketing\App\Enums\AssignmentStatus;
use Lareon\Modules\Ticketing\App\Enums\AssignmentType;
use Lareon\Steward\App\Models\Admin;

#[Fillable(['ticket_id', 'type', 'from_admin_id', 'to_admin_id', 'reason', 'status', 'decision_reason', 'decided_at', 'ticket_id',])]
class TicketAssignment extends Model
{
    protected $table = 'ticketing_assignments';

    protected function casts(): array
    {
        return [
            'type'       => AssignmentType::class,
            'status'     => AssignmentStatus::class,
            'decided_at' => 'datetime',
        ];
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function fromAdmin(): BelongsTo
    {
        return $this->belongsTo(config('ticketing.admin_model'), 'from_admin_id');
    }

    public function toAdmin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'to_admin_id');
    }

    public function isPending(): bool
    {
        return $this->status === AssignmentStatus::Pending;
    }
}
