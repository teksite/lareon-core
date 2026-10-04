<?php

namespace Lareon\Steward\App\Providers;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Lareon\Steward\App\Service\SlowQueryLogger;

class SlowQueryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SlowQueryLogger::class, fn () => new SlowQueryLogger());
    }

    public function boot(SlowQueryLogger $slowQueryLogger,): void {
        DB::listen(static function (QueryExecuted $query) use ($slowQueryLogger): void {
                $slowQueryLogger->log($query);
            }
        );
    }
}
