<?php

namespace Lareon\Modules\Auth\App\Logics;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Teksite\Authorize\Models\Role;
use Teksite\Handler\Actions\ServiceWrapper;
use Teksite\Handler\contracts\ServiceResult;
use Teksite\Handler\Services\FetchDataService;


class RoleLogic
{
    /**
     * @throws \Throwable
     */
    public function all(mixed $fetchData = []): ServiceResult
    {
        return ServiceWrapper::make(false)
                             ->do(fn() => FetchDataService::get(Role::class, 'title'))
                             ->run();

    }

    public function first(array $inputs = []) {}

    /**
     * @throws \Throwable
     */
    public function create(array $inputs = [])
    {

        return ServiceWrapper::make(false)->do(function () use ($inputs) {
            $role = Role::create(Arr::except($inputs, 'permissions'));
            $role->permissions()->attach($inputs['permissions'] ?? []);
            $this->cache();
            return $role;
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function update(Role $role, array $inputs = [])
    {
        return ServiceWrapper::make(false)->do(function () use ($role, $inputs) {
            $role->update(Arr::except($inputs, 'permissions'));
            $role->permissions()->sync($inputs['permissions'] ?? []);
            $this->cache();
            return $role->refresh();
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function delete(Role $role)
    {
        return ServiceWrapper::make(false)
                             ->do(function () use ($role) {
                                 $role->delete();
                                 $this->cache();
                                 return true;
                             })
                             ->run();
    }

    protected function cache(): void
    {
        Cache::forget('roles');
        Cache::rememberForever('roles', function () {
            return Role::query()->pluck('title' ,'id')->toArray();
        });
    }

}

