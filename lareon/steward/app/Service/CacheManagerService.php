<?php

namespace Lareon\Steward\App\Service;

use Illuminate\Support\Facades\Artisan;
use InvalidArgumentException;
use Lareon\Steward\App\Enums\CacheAction;
use Lareon\Steward\App\Enums\CacheType;
use Lareon\Steward\App\Enums\MenuAreaType;
use Lareon\Steward\App\Events\MenuRegisteringEvent;

class CacheManagerService
{
    public function run(CacheType $type, CacheAction $action): int
    {

        $command = match ($type) {
            CacheType::AUTH          => match ($action) {
                CacheAction::CLEAR => 'auth:clear-resets',
                default            => $this->invalid(),
            },

            CacheType::CACHE         => match ($action) {
                CacheAction::CLEAR  => 'cache:clear',
                CacheAction::FORGET => 'cache:forget',
                CacheAction::PRUNE  => 'cache:prune-stale-tags',
                CacheAction::PURGE  => 'cache:purge',
                default             => $this->invalid(),
            },

            CacheType::CONFIG        => match ($action) {
                CacheAction::STORE => 'config:cache',
                CacheAction::CLEAR => 'config:clear',
                default            => $this->invalid(),
            },

            CacheType::EVENT         => match ($action) {
                CacheAction::STORE => 'event:cache',
                CacheAction::CLEAR => 'event:clear',
                default            => $this->invalid(),
            },

            CacheType::OPTIMIZE      => match ($action) {
                CacheAction::STORE => 'optimize',
                CacheAction::CLEAR => 'optimize:clear',
                default            => $this->invalid(),
            },

            CacheType::ROUTE         => match ($action) {
                CacheAction::STORE => 'route:cache',
                CacheAction::CLEAR => 'route:clear',
                default            => $this->invalid(),
            },

            CacheType::SANCTUM       => match ($action) {
                CacheAction::CLEAR => 'sanctum:prune-expired',
                default            => $this->invalid(),
            },

            CacheType::SCHEDULE      => match ($action) {
                CacheAction::CLEAR => 'schedule:clear-cache',
                default            => $this->invalid(),
            },

            CacheType::VIEW          => match ($action) {
                CacheAction::STORE => 'view:cache',
                CacheAction::CLEAR => 'view:clear',
                default            => $this->invalid(),
            },

            CacheType::COMPILED_FILE => match ($action) {
                CacheAction::CLEAR => 'clear-compiled',
                default            => $this->invalid(),
            },
        };

        return Artisan::call($command);
    }

    protected function invalid(): never
    {
        throw new InvalidArgumentException(
            'Invalid cache action.'
        );
    }
}
