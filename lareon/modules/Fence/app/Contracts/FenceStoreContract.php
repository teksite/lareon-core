<?php

namespace Lareon\Modules\Fence\App\Contracts;

interface FenceStoreContract
{
    /**
     * Get all stored IP addresses.
     *
     * @return array
     */
    public function all(): array;

    /**
     * Get the first matching IP address.
     *
     * @param string $ip
     * @return array|null
     */
    public function first(string $ip): ?array;

    /**
     * Store a new IP address.
     *
     * @param array $data
     * @return array
     */
    public function create(array $data): array;

    /**
     * Delete IP addresses.
     *
     * @param array $ips
     * @return int
     */
    public function delete(array $ips): int;

    /**
     * Determine whether an IP exists.
     *
     * @param string $ip
     * @return bool
     */
    public function exists(string $ip): bool;
}
