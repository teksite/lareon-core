<?php

namespace Lareon\Steward\App\Traits;

trait HasMenu
{
    protected function can(string $permission): bool
    {
        return auth()->check() && auth()->user()->can($permission);
    }

    protected function admin(): bool
    {
        return auth()->check();
    }

    protected function auth(): bool
    {
        return auth()->check();
    }

    protected function badge(\Closure $callback): ?string
    {
        try {
            $count = $callback();
            return $count > 0 ? (string)$count : null;
        } catch (\Throwable) {
            return null;
        }
    }
}
