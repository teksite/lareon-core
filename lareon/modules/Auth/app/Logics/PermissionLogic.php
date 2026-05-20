<?php

namespace Lareon\Modules\Auth\App\Logics;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
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

    public function first(array $inputs = [])
    {

    }

    /**
     * @throws \Throwable
     */
    public function create(array $inputs = [])
    {

        return ServiceWrapper::make(false)
                             ->do(fn() => Permission::create($inputs))
                             ->run();
    }

    /**
     * @throws \Throwable
     */
    public function update(Permission $permission, array $inputs = [])
    {
        return ServiceWrapper::make(false)
                             ->do(fn() => $permission->update($inputs))
                             ->run();
    }

    /**
     * @throws \Throwable
     */
    public function delete(Permission $permission)
    {
        return ServiceWrapper::make(false)
                             ->do(fn() => $permission->delete())
                             ->run();
    }

}

