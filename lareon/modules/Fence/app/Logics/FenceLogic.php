<?php

namespace Lareon\Modules\Fence\App\Logics;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Lareon\Modules\Fence\App\Contracts\FenceStoreContract;
use Teksite\Handler\Services\ServiceWrapper;


class FenceLogic
{

    public string $storeType;

    public function __construct(protected FenceStoreContract $store,) {}

    public function all(mixed $fetchData = [],)
    {
        return ServiceWrapper::make(true)->do(fn() => $this->store->all())->run();
    }

    public function first(array $inputs = [],)
    {
        return ServiceWrapper::make(true)->do(fn() => $this->store->first($ip))->run();
    }

    public function create(array $inputs = [],)
    {
        return ServiceWrapper::make(true)->do(fn() => $this->store->create($inputs))->run();
    }


    public function delete(array $inputs,)
    {
        return ServiceWrapper::make(true)->do(fn() => $this->store->delete($inputs))->run();
    }

    public function exists(string $ip,): mixed
    {
        return ServiceWrapper::make(true)->do(fn() => $this->store->exists($ip))->run();
    }


}

