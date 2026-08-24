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
    public function all(mixed $fetchData = []) {

    }

    public function first(array $inputs = []) {

    }



    public function delete(Model $model) {}

}

