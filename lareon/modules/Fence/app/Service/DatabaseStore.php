<?php

namespace Lareon\Modules\Fence\App\Service;

use Lareon\Modules\Fence\App\Models\Fence;

class DatabaseStore
{
    public function all(): array
    {
        return Fence::query()->get(['ip_address', 'type'])
                    ->map(fn(Fence $fence,) => ['ip_address' => $fence->ip_address, 'type' => $fence->type,])
                    ->values()
                    ->all();
    }

    public function first(string $ip,): ?array
    {
        $fence = Fence::query()
                      ->where('ip_address', $ip)
                      ->first(['ip_address', 'type']);

        if (!$fence) return null;

        return [
            'ip_address' => $fence->ip_address,
            'type'       => $fence->type,
        ];
    }

    public function create(array $data,): array
    {
        $fence = Fence::query()->create([
            'ip_address' => $data['ip_address'],
            'type'       => $data['type'],
        ]);

        return [
            'ip_address' => $fence->ip_address,
            'type'       => $fence->type,
        ];
    }

    public function delete(array $ips,): int
    {
        return Fence::query()->whereIn('ip_address', $ips)->delete();
    }

    public function exists(string $ip,): bool
    {
        return Fence::query()->where('ip_address', $ip)->exists();
    }
}
