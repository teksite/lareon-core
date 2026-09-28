<?php

namespace Lareon\Modules\Fence\App\Providers;

use Illuminate\Cache\DatabaseStore;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Http\Kernel;
use Lareon\Modules\Fence\App\Http\Middleware\FenceMiddleware;
use Lareon\Modules\Fence\App\Contracts\FenceStoreContract;
use Lareon\Modules\Fence\App\Services\DatabaseStoreDriver;
use Lareon\Modules\Fence\App\Services\FileStoreDriver;
use Teksite\Module\Providers\Support\BaseModuleServiceProvider as ServiceProvider;

class FenceServiceProvider extends ServiceProvider
{
    /**
     * The name of the module.
     *
     * @var string
     */
    protected string $moduleName = "Fence";

    /**
     * The lowercase version of the module name.
     *
     * @var string
     */
    protected string $lowerModuleName = "fence";

    /**
     * Module type (self|steward)
     *
     * @var string
     */
    protected string $type = "steward";


    /**
     * Command classes to register.
     *
     * @var string[]
     */
    protected array $commands = [];

    /**
     * Provider classes to register.
     *
     * @var string[]
     */
    protected array $providers = [
        EventServiceProvider::class,
        //RouteServiceProvider::class,
    ];


    /**
     * Define module schedules.
     */
    protected function configureSchedules(Schedule $schedule,): void
    {
        // $schedule->command('inspire')->hourly();
        // ...
    }


    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        parent::boot();

       if (config('fence.enabled')) $this->app->make(Kernel::class)->pushMiddleware(FenceMiddleware::class);

    }

    /**
     * register the application events.
     */
    public function register(): void
    {
        parent::register();
        $this->registerFenceStore();
    }

    private function registerFenceStore(): void
    {
        $this->app->singleton(FenceStoreContract::class, function ($app,) {
            return match (config('fence.store_type', 'file')) {
                'database' => $app->make(DatabaseStoreDriver::class),
                'file'     => $app->make(FileStoreDriver::class, ['path' => config('fence.store_file'),]),
                default    => throw new \InvalidArgumentException(sprintf('Unsupported Fence store type [%s].', config('fence.store_type')),),
            };
        });
    }
}
