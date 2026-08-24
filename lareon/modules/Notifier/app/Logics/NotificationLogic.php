<?php

namespace Lareon\Modules\Notifier\App\Logics;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Lareon\Modules\Notifier\App\Jobs\PrepareAwarenessNotificationJob;
use Lareon\Modules\User\App\Models\User;
use Teksite\Authorize\Models\Role;
use Teksite\Handler\Actions\ServiceWrapper;
use Teksite\Handler\Services\FetchDataService;


class NotificationLogic
{
    /**
     * @throws \Throwable
     * @throws BindingResolutionException
     */
    public function allByUser(mixed $fetchData = [], Authenticatable|User $user = null)
    {
        $user ??= auth()->user();
       return ServiceWrapper::make(false)->do(function () use ($user) {
            return FetchDataService::get($user->notifications() , ['title']);
        })->run();
    }

    public function first(array $inputs = []) {}


    public function delete(Model $model) {}

}

