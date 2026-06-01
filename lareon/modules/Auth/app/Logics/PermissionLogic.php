<?php

namespace Lareon\Modules\Auth\App\Logics;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Teksite\Authorize\Models\Permission;
use Teksite\Handler\Actions\ServiceWrapper;
use Teksite\Handler\contracts\ServiceResult;
use Teksite\Handler\Services\FetchDataService;


class PermissionLogic
{
    /**
     * @throws \Throwable
     */
    public function all(mixed $fetchData = []): ServiceResult
    {
        return ServiceWrapper::make(false)
                             ->do(fn() => FetchDataService::get(Permission::class, 'title'))
                             ->run();

    }

    public function first(array $inputs = []) {}

    /**
     * @throws \Throwable
     */
    public function create(array $inputs = [])
    {

        return ServiceWrapper::make(false)->do(function () use ($inputs) {
            $permission = Permission::create($inputs);
            $this->renewAllPermissions();
            return $permission;
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function update(Permission $permission, array $inputs = [])
    {
        return ServiceWrapper::make(false)->do(function () use ($inputs, $permission) {
            $permission->update($inputs);
            return $permission->refresh();
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function delete(Permission $permission)
    {
        return ServiceWrapper::make(false)->do(function () use ($permission) {
            $permission->delete();
            $this->renewAllPermissions();
        })->
        run();
    }


    public function getAll(): array
    {
        return Cache::remember('all_permissions', 60 * 60 * 24 * 30, function () {
            return Permission::query()->pluck('title', 'id')->toArray();
        });
    }

    public function clearCache(): void
    {
        Cache::forget('all_permissions');
    }

    public function renewAllPermissions(): void
    {
        $this->clearCache();
        $this->getAll();
    }


}

