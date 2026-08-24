<?php

namespace Lareon\Modules\Notifier\App\Logics;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Lareon\Modules\Notifier\App\Jobs\PrepareAwarenessNotificationJob;
use Lareon\Modules\User\App\Models\User;
use Teksite\Authorize\Models\Role;
use Teksite\Handler\Actions\ServiceWrapper;


class NotificationLogic
{
    public function all(mixed $fetchData = []) {}

    public function first(array $inputs = []) {}

    /**
     * @throws \Throwable
     * @throws BindingResolutionException
     */
    public function sendNotifications(array $inputs = [])
    {
        $title = $inputs['title'];
        $message = $inputs['message'] ?? '';
        $roleIds = $inputs['roles'] ?? [];
        $userIds = $inputs['users'] ?? [];
        $channels = $inputs['via'] ?? [];
        PrepareAwarenessNotificationJob::dispatch(title: $title,
            message: $message,
            roleIds: $roleIds,
            userIds: $userIds,
            channels: $channels,);

    }


    public function delete(Model $model) {}

}

