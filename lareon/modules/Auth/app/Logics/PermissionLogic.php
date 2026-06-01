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
        $tree = [];

        $permissions = $this->getAll();
        foreach ($permissions as $permission) {
            $parts = explode('.', $permission);
            $current = &$tree;

            foreach ($parts as $index => $part) {
                $fullPath = implode('.', array_slice($parts, 0, $index + 1));

                if (!isset($current[$part])) {
                    $current[$part] = [
                        'name' => $part,
                        'full_path' => $fullPath,
                        'is_leaf' => ($index === count($parts) - 1),
                        'level' => $index,
                        'children' => [],
                        'has_children' => false
                    ];
                }

                if (in_array($fullPath, $permissions) && $index === count($parts) - 1) {
                    $current[$part]['is_permission_itself'] = true;
                }
                $current = &$current[$part]['children'];
            }
        }

        $this->processTree($tree);

        return $tree;
    }


    private function processTree(&$nodes): void
    {
        foreach ($nodes as &$node) {
            if (!empty($node['children'])) {
                $node['has_children'] = true;
                $this->processTree($node['children']);
            } else {
                $node['has_children'] = false;
            }
        }

        usort($nodes, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });
    }
}


