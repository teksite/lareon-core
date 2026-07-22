<?php

namespace Lareon\Modules\FileManager\App\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Teksite\Module\Providers\Support\BaseModuleServiceProvider as ServiceProvider;


class FileManagerServiceProvider extends ServiceProvider
{
    /**
     * The name of the module.
     *
     * @var string
     */
    protected string $moduleName = "FileManager";

    /**
     * The lowercase version of the module name.
     *
     * @var string
     */
    protected string $lowerModuleName = "filemanager";

    /**
     * Module type (self|steward)
     *
     * @var string
     */
    protected string $type = "self";


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
        RouteServiceProvider::class,
    ];


    /**
     * Define module schedules.
     */
    protected function configureSchedules(Schedule $schedule): void
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
    }

    /**
     * register the application events.
     */
    public function register(): void
    {
        parent::register();
    }
}
