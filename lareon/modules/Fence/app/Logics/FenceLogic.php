<?php

namespace Lareon\Modules\Fence\App\Logics;

use Lareon\Modules\Fence\App\Contracts\FenceStoreContract;
use Teksite\Handler\Services\ServiceWrapper;

class FenceLogic
{
    public function __construct(protected FenceStoreContract $store,) {}

    /**
     * @throws \Throwable
     */
    public function all(array $filters = [], int $perPage = 25)
    {
        return ServiceWrapper::make(true)->do(fn() => $this->store->all($filters, $perPage))->run();
    }

    /**
     * @throws \Throwable
     */
    public function first(string $ip,)
    {
        return ServiceWrapper::make(true)->do(fn() => $this->store->first($ip))->run();
    }

    /**
     * @throws \Throwable
     */
    public function create(array $inputs = [],)
    {
        return ServiceWrapper::make(true)->do(fn() => $this->store->create($inputs))->run();
    }

    /**
     * @throws \Throwable
     */
    public function delete(array|string $ips,)
    {
        $ips=(array)$ips;
        return ServiceWrapper::make(true)->do(fn() => $this->store->delete($ips))->run();
    }

    /**
     * @throws \Throwable
     */
    public function exists(string $ip,)
    {
        return ServiceWrapper::make(true)->do(fn() => $this->store->exists($ip))->run();
    }
}
