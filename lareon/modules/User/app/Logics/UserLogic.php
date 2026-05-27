<?php

namespace Lareon\Modules\User\App\Logics;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Lareon\Modules\User\App\Models\User;
use Teksite\Authorize\Models\Role;
use Teksite\Handler\Actions\ServiceWrapper;
use Teksite\Handler\contracts\ServiceResult;
use Teksite\Handler\Services\FetchDataService;


class UserLogic
{
    /**
     *
     * @throws \Throwable
     */
    public function all(mixed $fetchData = []): ServiceResult
    {
        return ServiceWrapper::make(false)
                             ->do(fn() => FetchDataService::get(User::class, ['name', 'email', 'phone']))
                             ->run();
    }

    /**
     * @throws BindingResolutionException
     * @throws \Throwable
     */
    public function first(array $inputs = [] , bool $any = true)
    {
        return ServiceWrapper::make(false)->do(function () use ($inputs) {
            $query = User::query();
            foreach ($inputs as $key => $value) {
                $query->where($key, $value);
            }
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function create(array $inputs = [])
    {
        return ServiceWrapper::make(true)->do(function () use ($inputs) {
            $inputs['slug'] ??= strtolower(uniqid() . '-' .Str::random(4));
            $user = User::create($inputs);
            $rolesIds = $this->assignRole($user, config('general.default_user_role', 'user'));
            return $user;
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function update(User $user, array $inputs = [])
    {
        return ServiceWrapper::make(false)->do(function () use ($user, $inputs) {
            User::update(Arr::except($inputs, ['permissions', 'roles']));
            return $user->refresh();
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function delete(User $user)
    {
        return ServiceWrapper::make(false)->do(function () use ($user) {
            $user->roles()->detach();
            $user->delete();
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function assignRole(User $user, string|int|Role $inputs, string $action = 'creating')
    {
        return ServiceWrapper::make(false)->do(function () use ($inputs, $action, $user) {
            $roleArray = $user->assignRole($inputs);
            if (empty($roleArray)) {
                throw new \Exception("the user with id: " . $user->id . " has no role => attaching role is failed in $action the user");
            }
        })->run();
    }

}

