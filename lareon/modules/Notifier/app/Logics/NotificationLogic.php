<?php

namespace Lareon\Modules\Notifier\App\Logics;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
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
    public function prepareUserQuery(array $inputs = [])
    {
        return ServiceWrapper::make(false)->do(function () use ($inputs) {
            $roleIds = array_filter($inputs['roles'] ?? []);
            $userIds = array_filter($inputs['users'] ?? []);

            return User::query()->where(function ($query) use ($roleIds, $userIds) {
                if ($roleIds) {
                    $query->whereHas('roles', function ($query) use ($roleIds) {
                        $query->whereIn('roles.id', $roleIds);
                    });
                }

                if ($userIds) {
                    $query->orWhereIn('id', $userIds);
                }
            });

        })->run();
    }


    public function delete(Model $model) {}

}

