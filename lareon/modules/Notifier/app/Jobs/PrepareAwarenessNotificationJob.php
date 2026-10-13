<?php

namespace Lareon\Modules\Notifier\App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Notification;
use Lareon\Modules\Notifier\App\Enums\NotificationEnum;
use Lareon\Modules\Notifier\App\Notifications\AwarenessNotification;
use Lareon\Modules\User\App\Models\User;

class PrepareAwarenessNotificationJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    public int $timeout = 900;

    public function __construct(public string $title, public string $message, public NotificationEnum $type, public mixed $creator, public array $channels = [], public array $roleIds = [], public array $userIds = []) {}

    public function handle(): void
    {
        $roleIds = array_filter($this->roleIds);
        $userIds = array_filter($this->userIds);

        if (!$roleIds && !$userIds) return;

        $usersQuery = User::query()->where(function ($query) use ($roleIds, $userIds) {
            $query->when($roleIds, fn($q) => $q->whereHas(
                'roles', fn($r) => $r->whereIn('id', $roleIds)
            ));
            $query->when($userIds, fn($q) => $q->orWhereIn('id', $userIds));
        })->chunkById(1000, function ($users) {
            Notification::send($users, new AwarenessNotification(
                title   : $this->title,
                message : $this->message,
                type    : $this->type,
                channels: $this->channels,
                meta    : ['creator' => $this->creator],
            ));
        });

    }
}
