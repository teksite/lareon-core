<?php

namespace Lareon\Modules\Auth\App\Logics;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Teksite\Authorize\Models\Permission;
use Teksite\Handler\Actions\ServiceWrapper;
use Teksite\Handler\contracts\ServiceResult;
use Teksite\Handler\Services\FetchDataService;


class PermissionLogic
{
    /**
     * @throws \Throwable
     */
    public function all(mixed $fetchData = []): ServiceResult
    {
        return ServiceWrapper::make(false)
                             ->do(fn() => FetchDataService::get(Permission::class, 'title'))
                             ->run();

    }

    public function first(array $inputs = []) {}

    /**
     * @throws \Throwable
     */
    public function create(array $inputs = [])
    {

        return ServiceWrapper::make(false)->do(function () use ($inputs) {
            $permission = Permission::create($inputs);
            $this->renewAllPermissions();
            return $permission;
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function update(Permission $permission, array $inputs = [])
    {
        return ServiceWrapper::make(false)->do(function () use ($inputs, $permission) {
            $permission->update($inputs);
            return $permission->refresh();
        })->run();
    }

    /**
     * @throws \Throwable
     */
    public function delete(Permission $permission)
    {
        return ServiceWrapper::make(false)->do(function () use ($permission) {
            $permission->delete();
            $this->renewAllPermissions();
        })->
        run();
    }


    public function getAll(): array
    {
        return Cache::remember('all_permissions', 60 * 60 * 24 * 30, function () {
            return Permission::query()->pluck('title', 'id')->toArray();
        });
    }

    public function clearCache(): void
    {
        Cache::forget('all_permissions');
    }

    public function renewAllPermissions(): void
    {
        $this->clearCache();
        $this->getAll();
    }


    public function tree(): array
    {
        $nodes = [];
        $index = [];

        foreach ($this->getAll() as $id=>$title) {
            $parts = explode('.', $title);
            $path = '';

            foreach ($parts as $i => $part) {
                $path = $path === '' ? $part : $path . '.' . $part;

                if (!isset($index[$path])) {

                    $isRealNode = ($i === count($parts) - 1);

                    $index[$path] = [
                        'id' => $isRealNode ? $id : null,
                        'title' => $path,
                        'children' => [],
                    ];
                }

                if ($i > 0) {
                    $parentPath = implode('.', array_slice($parts, 0, $i));

                    $index[$parentPath]['children'][$path] = &$index[$path];
                }
            }

            if (count($parts) === 1) {
                $nodes[$title] = &$index[$title];
            }
        }

        return array_values($this->normalize($nodes));
    }

    private function normalize(array $nodes): array
    {
        foreach ($nodes as &$node) {
            if (!empty($node['children'])) {
                $node['children'] = array_values($this->normalize($node['children']));
            }
        }

        return $nodes;
    }
}
