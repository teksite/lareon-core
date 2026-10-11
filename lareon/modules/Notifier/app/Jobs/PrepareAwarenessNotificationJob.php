<?php

namespace Lareon\Modules\Notifier\App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Lareon\Modules\Notifier\App\Notifications\AwarenessNotification;
use Lareon\Modules\User\App\Models\User;
use Lareon\Steward\App\Models\Admin;

class PrepareAwarenessNotificationJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 5;

    public function __construct(public string $title, public string $message, public mixed $creator, public array $channels, public array $roleIds = [], public array $userIds = []) {}

    public function handle(): void
    {
        $roleIds = array_filter($this->roleIds);
        $userIds = array_filter($this->userIds);

        if (!$roleIds && !$userIds) return;

        $usersQuery = User::query()->where(function ($query) use ($roleIds, $userIds) {

            if ($roleIds) {
                $query->whereHas('roles', function ($query) use ($roleIds) {
                    $query->whereIn('id', $roleIds);
                });
            }
            if ($userIds) $query->orWhereIn('id', $userIds);

        });

        $usersQuery->chunkById(1000, function ($users) {
            foreach ($users as $user) {
                $user->notify(new AwarenessNotification(title: $this->title, message: $this->message, channels: $this->channels, meta: ['creator'=>$this->creator]));
            }
        });
    }
}
