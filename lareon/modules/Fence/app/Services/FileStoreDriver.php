<?php

namespace Lareon\Modules\Fence\App\Services;

use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Lareon\Modules\Fence\App\Contracts\FenceStoreContract;
use Lareon\Modules\Fence\App\Enums\GuardType;
use Lareon\Modules\Fence\App\Models\Fence;
use RuntimeException;

class FileStoreDriver implements FenceStoreContract
{
    public function __construct(protected string $path,) {}

    public function all(array $filters = [], int $perPage = 25,): LengthAwarePaginator
    {
        $search = trim((string)($filters['search'] ?? ''));
        $type = $this->normalizeType($filters['type'] ?? null);

        $records = collect($this->read())
            ->when($search !== '', fn($c,) => $c->filter(fn($r,) => str_contains($r['ip_address'], $search)))
            ->when($type !== null, fn($c,) => $c->filter(fn($r,) => (int)$r['type'] === $type->value))
            ->sortByDesc('created_at')
            ->values();

        $page = Paginator::resolveCurrentPage();

        return (new LengthAwarePaginator(
            $records->forPage($page, $perPage)->map(fn($r,) => $this->hydrate($r))->values(),
            $records->count(),
            $perPage,
            $page,
            ['path' => Paginator::resolveCurrentPath()],
        ))->withQueryString();
    }

    public function first(string $ip,): ?Fence
    {
        $record = $this->read()[$ip] ?? null;

        return $record ? $this->hydrate($record) : null;
    }

    public function create(array $inputs,): Fence
    {
        $ip = $inputs['ip_address'];
        $type = $this->normalizeType($inputs['type']);

        return $this->mutate(function (array $records,) use ($ip, $type) {
            $records[$ip] = [
                'ip_address' => $ip,
                'type'       => $type->value,
                'created_at' => $records[$ip]['created_at'] ?? now()->toDateTimeString(),
            ];

            return [$records, $this->hydrate($records[$ip])];
        });
    }

    public function delete(array $ips,): int
    {
        return $this->mutate(function (array $records,) use ($ips) {
            $before = count($records);
            foreach ($ips as $ip) {
                unset($records[$ip]);
            }

            return [$records, $before - count($records)];
        });
    }

    public function exists(string $ip,): bool
    {
        return isset($this->read()[$ip]);
    }

    /* ---------------------------------------------------------------- */

    private function hydrate(array $record,): Fence
    {
        return (new Fence())->forceFill([
            'ip_address' => $record['ip_address'],
            'type'       => (int)$record['type'],
            'created_at' => Carbon::parse($record['created_at']),
        ]);
    }

    private function normalizeType(mixed $type,): ?GuardType
    {
        if ($type instanceof GuardType) {
            return $type;
        }

        return ($type === null || $type === '' || !is_numeric($type))
            ? null
            : GuardType::tryFrom((int)$type);
    }

    /** خواندن با shared lock */
    private function read(): array
    {
        if (!is_file($this->path)) {
            return [];
        }

        $handle = fopen($this->path, 'r');
        if ($handle === false) {
            throw new RuntimeException("Cannot open fence file [{$this->path}].");
        }

        try {
            flock($handle, LOCK_SH);
            $raw = stream_get_contents($handle);
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }

        return $this->decode($raw);
    }

    /**
     * خواندن + تغییر + نوشتن در یک lock اختصاصی.
     * callback باید [newRecords, result] برگرداند.
     */
    private function mutate(callable $callback,): mixed
    {
        $dir = dirname($this->path);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $handle = fopen($this->path, 'c+');
        if ($handle === false) {
            throw new RuntimeException("Cannot open fence file [{$this->path}].");
        }

        try {
            flock($handle, LOCK_EX);

            [$records, $result] = $callback($this->decode(stream_get_contents($handle)));

            ftruncate($handle, 0);
            rewind($handle);
            fwrite($handle, json_encode($records, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            fflush($handle);

            return $result;
        } finally {
            flock($handle, LOCK_UN);
            fclose($handle);
        }
    }

    private function decode(string|false $raw,): array
    {
        if ($raw === false || trim($raw) === '') {
            return [];
        }

        $data = json_decode($raw, true);

        return is_array($data) ? $data : [];
    }
}
