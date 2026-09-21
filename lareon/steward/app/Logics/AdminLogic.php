<?php

namespace Lareon\Steward\App\Logics;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Arr;
use Lareon\Steward\App\Models\Admin;
use Teksite\Authorize\Models\Role;
use Teksite\Handler\Contracts\ServiceResultContract;
use Teksite\Handler\Facade\FetchData;
use Teksite\Handler\Services\ServiceWrapper;
use Throwable;


class AdminLogic
{

    /**
     * @throws Throwable
     */
    public function all(mixed $fetchData = [],): ServiceResultContract
    {
        return ServiceWrapper::make(false)
                             ->do(fn() => FetchData::get(Admin::class, ['name', 'email',]))
                             ->run();
    }

    /**
     * @throws Throwable
     */
    public function allByParent(mixed $fetchData = [],): ServiceResultContract
    {
        return ServiceWrapper::make(false)
                             ->do(fn() => FetchData::get(auth()->user()->children(), ['name', 'email',]))
                             ->run();
    }


    /**
     * @throws BindingResolutionException
     * @throws Throwable
     */
    public function first(array $inputs = [], bool $any = true,): ServiceResultContract
    {
        return ServiceWrapper::make(false)->do(function () use ($inputs) {
            $query = Admin::query();
            foreach ($inputs as $key => $value) {
                $query->where($key, $value);
            }
        })->run();
    }

    /**
     * @throws Throwable
     */
    public function create(array $inputs = [],): ServiceResultContract
    {
        return ServiceWrapper::make(true)->do(function () use ($inputs) {
            $inputs['parent_id'] = auth('admin')->id();
            $admin = Admin::create($inputs);
            $this->assignRole($admin, 'manager');
            return $admin;
        })->run();
    }

    /**
     * @throws Throwable
     */
    public function update(Authenticatable|Admin $admin, array $inputs = [],): ServiceResultContract
    {
        return ServiceWrapper::make(false)->do(function () use ($admin, $inputs) {
            if (!isset($inputs['password']) || $inputs['password'] === null) unset($inputs['password']);

            $admin->fill(Arr::except($inputs, ['permissions', 'roles']));
            $admin->save();
            return $admin->refresh();
        })->run();
    }


    /**
     * @throws Throwable
     */
    public function delete(Authenticatable|Admin $admin,): ServiceResultContract
    {
        return ServiceWrapper::make(false)->do(function () use ($admin) {
            $admin->roles()->detach();
            $admin->delete();
        })->run();
    }


    /**
     * @throws Throwable
     */
    public function assignRole(Authenticatable|Admin $admin, string|int|Role $inputs, string $action = 'creating',): ServiceResultContract
    {
        return ServiceWrapper::make(false)->do(function () use ($inputs, $action, $admin) {
            $roleArray = $admin->assignRole($inputs);
            if (empty($roleArray)) {
                throw new \Exception("the user with id: ".$admin->id." has no role => attaching role is failed in $action the user");
            }
        })->run();
    }


    /**
     * @throws BindingResolutionException
     * @throws Throwable
     */
    public function updateACL(Authenticatable|Admin $admin, array $inputs,)
    {
        return ServiceWrapper::make(false)->do(function () use ($inputs, $admin) {
            $roles = $inputs['roles'] ?? [];
            $permissions = $inputs['permissions'] ?? [];
            $roleArray = $admin->assignRole($roles);
            $permissionsArray = $admin->syncPermissions($permissions);

            return [
                'roles'       => $roleArray,
                'permissions' => $permissionsArray,
            ];

        })->run();
    }
}



