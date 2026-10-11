<?php

namespace Lareon\Modules\Notifier\App\Logics;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Lareon\Modules\Notifier\App\Jobs\PrepareAwarenessNotificationJob;
use Lareon\Modules\User\App\Models\User;
use Teksite\Handler\Facade\FetchData;
use Teksite\Handler\Services\ServiceWrapper;


class NotificationLogic
{

    /**
     * @throws \Throwable
     * @throws BindingResolutionException
     */
    public function all(mixed $fetchData = [], Authenticatable|User $user = null)
    {
        return ServiceWrapper::make(false)->do(function () {
            return FetchData::get(DatabaseNotification::class, ['title'  ,'read_at']);
        })->run();
    }


    /**
     * @throws \Throwable
     * @throws BindingResolutionException
     */
    public function allByUser(mixed $fetchData = [], Authenticatable|User $user = null)
    {
        $user ??= auth()->user();
        return ServiceWrapper::make(false)->do(function () use ($user) {
            return FetchData::get($user->notifications(), ['title']);
        })->run();
    }

    public function first(array $inputs = []) {}


    public function delete(Model $model) {}

}

