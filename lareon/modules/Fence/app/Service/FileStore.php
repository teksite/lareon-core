<?php

namespace Lareon\Modules\Fence\App\Service;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;
use Lareon\Modules\Fence\App\Contracts\FenceStoreContract;
use Lareon\Modules\Fence\App\Enums\GuardType;



class FileFenceStore implements FenceStoreContract
{
    public function __construct(
        protected string $path,
    ) {}

    /**
     * @throws FileNotFoundException
     */
    public function all(): array
    {
        return array_map(
            fn(array $item,) => $this->normalize($item),
            $this->read(),
        );
    }

    /**
     * @throws FileNotFoundException
     */
    public function first(string $ip,): ?array
    {
        foreach ($this->read() as $item) {
            if ($item['ip_address'] === $ip) return $this->normalize($item);
        }

        return null;
    }

    public function create(array $data,): array
    {
        $items = $this->read();

        $item = [
            'ip_address' => $data['ip_address'],
            'type'       => $this->typeToInt($data['type']),
        ];

        $items[] = $item;

        $this->write($items);

        return $this->normalize($item);
    }

    public function delete(array $ips,): int
    {
        $items = $this->read();

        $ips = array_flip($ips);

        $remaining = [];
        $deleted = 0;

        foreach ($items as $item) {
            if (isset($ips[$item['ip_address']])) {
                $deleted++;
                continue;
            }

            $remaining[] = $item;
        }

        if ($deleted > 0) $this->write($remaining);


        return $deleted;
    }

    public function exists(string $ip,): bool
    {
        return $this->first($ip) !== null;
    }

    protected function normalize(array $item,): array
    {
        return [
            'ip_address' => $item['ip_address'],
            'type'       => GuardType::from((int)$item['type']),
        ];
    }

    protected function typeToInt(GuardType|int $type,): int
    {
        return $type instanceof GuardType ? $type->value : $type;
    }

    /**
     * @throws FileNotFoundException
     */
    protected function read(): array
    {
        if (!File::exists($this->path)) return [];

        $content = File::get($this->path);

        if (blank($content)) return [];

        $data = json_decode($content, true);

        return is_array($data) ? $data : [];
    }

    protected function write(array $data,): void
    {
        $directory = dirname($this->path);

        if (!File::exists($directory)) File::makeDirectory($directory, 0755, true);

        File::put($this->path, json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), true);
    }

}
