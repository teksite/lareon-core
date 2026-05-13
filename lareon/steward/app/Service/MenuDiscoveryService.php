<?php

namespace Lareon\Steward\App\Service;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Lareon\Steward\App\Contracts\MenuRegisteringContract;
use Lareon\Steward\App\Enums\MenuAreaType;
use Teksite\Module\Facade\Module;

class MenuDiscoveryService
{
    protected const string CACHE_KEY = 'menu_providers';
    protected int $ttl = 3600;

    public function get(MenuAreaType $area, bool $fresh = false): array
    {
        $key = self::CACHE_KEY . '_' . $area->value;

        $all = $this->discover($fresh);

        $filtered = array_filter($all, fn($p) => in_array($area, $p->areas()));
        usort($filtered, fn($a, $b) => $a->priority() <=> $b->priority());


        return $filtered;
    }

    protected function discover(bool $fresh = false): array
    {

        $providers = [];

        $modules= ['Steward' , ...Module::enables(true)];

        foreach ($modules as $module) {
            $file =$this->resolveMenuProviderFile($module);

            if ($file && !File::exists($file))   continue;
                $class = $this->resolveMenuProviderClass($module);

                if ($class && class_exists($class) && is_subclass_of($class, MenuRegisteringContract::class)) {
                    $providers[] = app($class);
            }
        }


        return $providers;
    }

    protected function resolveMenuProviderClass(string $module): ?string
    {
        return $module === 'Steward'
            ? steward_namespace() . '\\App\\Providers\\MenuProvider'
            : module_namespace($module) . '\\App\\Providers\\MenuProvider';

    }
    protected function resolveMenuProviderFile(string $module): ?string
    {
        return $module === 'Steward'
            ? steward_path('/app/Providers/MenuProvider.php')
            : module_path($module , '/app/Providers/MenuProvider.php');

    }

    public function clear(): void
    {
        Cache::forget(self::CACHE_KEY);
        foreach (MenuAreaType::cases() as $area) {
            Cache::forget(self::CACHE_KEY . '_' . $area->value);
        }
    }
}
