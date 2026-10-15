<?php

namespace Lareon\Modules\Ticketing\App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lareon\Modules\Ticketing\App\Enums\PriorityType;
use Lareon\Modules\Ticketing\App\Enums\TicketStatusType;
use Lareon\Modules\Ticketing\Database\Factory\TicketFactory;
use Lareon\Modules\User\App\Models\User;
use Lareon\Steward\App\Models\Admin;

#[UseFactory(TicketFactory::class)]
#[Fillable(['ticket_number', 'user_id', 'subject', 'priority', 'status', 'holder_admin_id', 'pending_admin_id', 'source', 'ai_handled', 'ai_last_confidence', 'closed_by_type', 'closed_by_id', 'closed_at', 'last_activity_at',])]
class Ticket extends Model
{
    /** @use HasFactory<TicketFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'ticketing_tickets';

    protected function casts(): array
    {
        return [
            'priority'           => PriorityType::class,
            'status'             => TicketStatusType::class,
            'ai_handled'         => 'boolean',
            'ai_last_confidence' => 'float',
            'closed_at'          => 'datetime',
            'last_activity_at'   => 'datetime',
        ];
    }


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function holderAdmin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'holder_admin_id');
    }

    public function pendingAdmin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'pending_admin_id');
    }


    public function scopeUnassigned(Builder $query): Builder
    {
        return $query->whereNull('holder_admin_id')->whereNull('pending_admin_id');
    }

    /**
     * tickets belongs to $admin
     *
     * @param Builder $query
     * @param int     $adminId
     * @return Builder
     */
    public function scopeHeldBy(Builder $query, int $adminId): Builder
    {
        return $query->where('holder_admin_id', $adminId);
    }

    /**
     * Tickets assigned to an admin and awaiting their decision
     */
    public function scopePendingFor(Builder $query, int $adminId): Builder
    {
        return $query->where('pending_admin_id', $adminId);
    }

    /**
     * All tickets an admin is authorized to view:
     * unassigned tickets, tickets they own, and tickets referred to them that are awaiting their decision.
     * This method is the central enforcement point for the “ticket privacy between admins” rule.
     */
    public function scopeVisibleToAdmin(Builder $query, int $adminId): Builder
    {
        return $query->where(function (Builder $q) use ($adminId): void {
            $q->whereNull('holder_admin_id')->whereNull('pending_admin_id')
              ->orWhere('holder_admin_id', $adminId)
              ->orWhere('pending_admin_id', $adminId);
        });
    }

    /**
     * ticket are not closed yet
     *
     * @param Builder $query
     * @return Builder
     *
     */
    public function scopeOpenOnly(Builder $query): Builder
    {
        return $query->where('status', '!=', TicketStatusType::Closed->value);
    }

    // ---------------------------------------------------------------------
    // Helperها
    // ---------------------------------------------------------------------

    public function isClosed(): bool
    {
        return $this->status === TicketStatusType::Closed || $this->status === TicketStatusType::Closed->value;
    }

    public function isHeldBy(int $adminId): bool
    {
        return $this->holder_admin_id === $adminId;
    }

    public function isPendingFor(int $adminId): bool
    {
        return $this->pending_admin_id === $adminId;
    }

    public function isUnassigned(): bool
    {
        return $this->holder_admin_id === null && $this->pending_admin_id === null;
    }

    public function isOwnedBy(int $userId): bool
    {
        return $this->user_id === $userId;
    }

}
