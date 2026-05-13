<?php

namespace Lareon\Steward\App\Service;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Lareon\Steward\App\Contracts\MenuRegisteringContract;
use Lareon\Steward\App\Enums\MenuAreaType;
use Lareon\Steward\App\Events\AdminMenuRegisteringEvent;
use Teksite\Module\Facade\Module;

class MenuDiscoveryService
{
    protected const string CACHE_KEY = 'menu_providers';
    protected int $ttl = 3600;

    public function get(MenuAreaType $area, bool $fresh = false): array
    {
        $key = self::CACHE_KEY . '_' . $area->value;

        if (!$fresh && Cache::has($key)) {
            return Cache::get($key);
        }

        $all = $this->discover($fresh);

        $filtered = array_filter($all, fn($p) => in_array($area, $p->areas()));
        usort($filtered, fn($a, $b) => $a->priority() <=> $b->priority());

        Cache::put($key, $filtered, $this->ttl);

        return $filtered;
    }

    protected function discover(bool $fresh = false): array
    {
        if (!$fresh && Cache::has(self::CACHE_KEY)) {
            return Cache::get(self::CACHE_KEY);
        }

        $providers = [];

        $modules= ['Steward' , ...Module::enables(true)];
        foreach ($modules as $module) {
            $menuDir = modulePath($module). DIRECTORY_SEPARATOR .'app'.DIRECTORY_SEPARATOR.'Menu';

            if ($menuDir && !File::exists($menuDir))   continue;


            foreach (File::files($menuDir) as $file) {
                $class = $this->resolveClass($file, basename($module));

                if ($class && class_exists($class) && is_subclass_of($class, MenuRegisteringContract::class)) {
                    $providers[] = app($class);
                }
            }
        }

        Cache::put(self::CACHE_KEY, $providers, $this->ttl);

        return $providers;
    }

    protected function resolveClass($file, string $module): ?string
    {
        $path = str_replace(base_path($file), '', $file->getPathname());
        $path = str_replace('/', '\\', $path);
        $path = preg_replace('/\.php$/', '', $path);

        return $path ? "LAREON\\{$module}\\{$path}" : null;
    }

    public function clear(): void
    {
        Cache::forget(self::CACHE_KEY);
        foreach (MenuAreaType::cases() as $area) {
            Cache::forget(self::CACHE_KEY . '_' . $area->value);
        }
    }
}
