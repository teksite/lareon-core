<?php

namespace Lareon\Modules\Fence\App\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use Lareon\Modules\Fence\App\Models\Fence;

interface FenceStoreContract
{
    /**
     * @param array{search?: string|null, type?: int|string|null} $filters
     */
    public function all(array $filters = [], int $perPage = 25): LengthAwarePaginator;

    public function first(string $ip): ?Fence;

    /**
     * @param array{ip_address: string, type: int|string|\Lareon\Modules\Fence\App\Enums\GuardType} $inputs
     */
    public function create(array $inputs): Fence;

    /**
     * @param string[] $ips
     * @return int deleted item
     */
    public function delete(array $ips): int;

    public function exists(string $ip): bool;
}
