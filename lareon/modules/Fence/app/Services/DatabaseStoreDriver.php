<?php

namespace Lareon\Modules\Fence\App\Services;


use Illuminate\Pagination\LengthAwarePaginator;
use Lareon\Modules\Fence\App\Contracts\FenceStoreContract;
use Lareon\Modules\Fence\App\Enums\GuardType;
use Lareon\Modules\Fence\App\Models\Fence;

class DatabaseStoreDriver implements FenceStoreContract
{
    public function all(array $filters = [], int $perPage = 25): LengthAwarePaginator
    {
        $search = trim((string) ($filters['search'] ?? ''));
        $type   = $this->normalizeType($filters['type'] ?? null);

        return Fence::query()
                    ->when($search !== '', fn($q) => $q->where('ip_address', 'like', "%{$search}%"))
                    ->when($type !== null, fn($q) => $q->where('type', $type->value))
                    ->latest()
                    ->latest('id')
                    ->paginate($perPage)
                    ->withQueryString();
    }

    public function first(string $ip): ?Fence
    {
        return Fence::query()->where('ip_address', $ip)->first();
    }

    public function create(array $inputs): Fence
    {
        return Fence::query()->updateOrCreate(
            ['ip_address' => $inputs['ip_address']],
            ['type' => $this->normalizeType($inputs['type'])],
        );
    }

    public function delete(array $ips): int
    {
        return Fence::query()->whereIn('ip_address', $ips)->delete();
    }

    public function exists(string $ip): bool
    {
        return Fence::query()->where('ip_address', $ip)->exists();
    }

    private function normalizeType(mixed $type): ?GuardType
    {
        if ($type instanceof GuardType) {
            return $type;
        }

        return ($type === null || $type === '' || !is_numeric($type))
            ? null
            : GuardType::tryFrom((int) $type);
    }
}
